<?php
/*
 * vim: ts=4 sw=4 et nowrap co=100
 */

/*
 *
 */
class ImporterFromMsAccessActivities
{
    /*
     *
     */
    public function __construct()
    {
        throw new Exception('This class is not intended to be instantiated.');
    }

    // Public functions. ///////////////////////////////////////////////////////////////////////

    /*
     *
     */
    public static function importRows($db, $srcRows)
    {
        self::$_staffNotFoundNamesFullAsKeys = array();

        self::_importLookupTableRows($db, $srcRows);

        echo 'Importing `activity` rows.';
        foreach ($srcRows as $srcRow) {
            echo '.';
            $idActivity = self::_importActivityRow($db, $srcRow);

            if ($idActivity !== null) {
                self::_importTaskRowsForRow($db, $idActivity, $srcRow);
                self::_importActivityLinkTableRowsForRow($db, $idActivity, $srcRow);
            }
        }
        echo "done.\n";

        echo "Staff not found in `staff` table.\n";
        $staffNotFoundNamesFull = array_keys(self::$_staffNotFoundNamesFullAsKeys);
        sort($staffNotFoundNamesFull);
        print_r($staffNotFoundNamesFull);
    }

    // Private functions. //////////////////////////////////////////////////////////////////////

    // Lookup table importing functions. -----------------------------------------------------//

    /*
     *
     */
    private static function _importLookupTableRows($db, $srcRows)
    {
        // Initialise.
        $valuesToImportAsKeysBySrcColumnName = array();
        foreach (array_keys(self::$_lookupTableNameBySrcColumnName) as $srcColumnName) {
            $valuesToImportAsKeysBySrcColumnName[$srcColumnName] = array();
        }

        // Fill.
        foreach ($srcRows as $srcRow) {
            $valuesBySrcColumnName = self::_getLookupTableValuesToImportBySrcColumnName($srcRow);

            foreach ($valuesBySrcColumnName as $srcColumnName => $values) {
                foreach ($values as $value) {
                    if ($value != '') {
                        $valuesToImportAsKeysBySrcColumnName[$srcColumnName][$value] = null;
                    }
                }
            }
        }

        // Insert.
        foreach ($valuesToImportAsKeysBySrcColumnName as $srcColumnName => $valuesAsKeys) {
            self::_importNamesIntoLookupTable(
                $db, array_keys($valuesAsKeys),
                self::$_lookupTableNameBySrcColumnName[$srcColumnName]
            );
        }
    }

    /*
     * NOTE
     * ----
     * 'Lookup tables' here means simple tables with no dependencies and only id and name columns.
     */
    private static function _getLookupTableValuesToImportBySrcColumnName($srcRow)
    {
        $valuesBySrcColumnName = array();

        foreach (array_keys(self::$_lookupTableNameBySrcColumnName) as $srcColumnName) {
            $valuesBySrcColumnName[$srcColumnName] = (
                (in_array($srcColumnName, self::$_namesOfColumnsToSplitOnCommas))?
                explode(',', $srcRow[$srcColumnName]): array($srcRow[$srcColumnName])
            );
        }

        return $valuesBySrcColumnName;
    }

    /*
     *
     */
    private static function _importNamesIntoLookupTable($db, $names, $tableName)
    {
        echo "Importing into lookup table `$tableName`...\n";

        $dstColumnName = 'name';

        foreach ($names as $name) {
            if (!self::_rowExistsInTable($db, $tableName, array($dstColumnName => $name))) {
                $db->insert($tableName, array($dstColumnName => $name));
                echo "   Inserted `$name`.\n";
            }
        }
    }

    /*
     *
     */
    private static function _rowExistsInTable($db, $tableName, $whereDetails)
    {
        $whereClauses = array();

        foreach ($whereDetails as $key => $value) {
            $whereClauses[] = $db->quoteIdentifier($key) . '=' . $db->quote($value);
        }

        $rows = $db->fetchAll(
            'SELECT EXISTS(
                 SELECT *
                 FROM ' . $db->quoteIdentifier($tableName) . '
                 WHERE ' . implode(' AND ', $whereClauses) . '
             ) AS `exists`'
        );

        if (count($rows) != 1) {
            throw new Exception('Unexpected number of rows returned by query.');
        }

        return ($rows[0]['exists'] == '1');
    }

    /*
     *
     */
    private static function _getIdLookupTableMatchingName($db, $lookupTableName, $name)
    {
        $rows = $db->fetchAll(
            'SELECT `id`
             FROM ' . $db->quoteIdentifier($lookupTableName) . '
             WHERE `name`=' . $db->quote($name)
        );

        if (count($rows) == 0) {
            throw new Exception("No row of table `$lookupTableName` found matching name '$name'.");
        }

        return $rows[0]['id'];
    }

    // Task table functions. -----------------------------------------------------------------//

    /*
     *
     */
    private static function _importTaskRowsForRow($db, $idActivity, $srcRow)
    {
        $taskTitles = explode(',', $srcRow['Tasks']);

        foreach ($taskTitles as $taskTitle) {
            if ($taskTitle == '') {
                continue;
            }

            $row = array(
                'idActivity' => $idActivity,
                'taskTitle'  => $taskTitle ,
                'idTaskType' => '1'
            );

            if (!self::_rowExistsInTable($db, 'task', $row)) {
                $db->insert('task', $row);
            }
        }
    }

    // Activity table functions. -------------------------------------------------------------//

    /*
     *
     */
    private static function _importActivityRow($db, $srcRow)
    {
        $idLookupTableBySrcColumnName = self::_getIdLookupTableBySrcColumnName($db, $srcRow);
        $datesBySrcColumnName         = self::_getSqlDateBySrcColumnName($db, $srcRow);
        $idStaffBySrcColumnName       = self::_getIdStaffBySrcColumnName($db, $srcRow);

        $row = array(
            'idStaff_creator'                        => self::ID_STAFF_TOM,
            'idStaff_updater'                        => self::ID_STAFF_TOM,
            'activityNo'                             => $srcRow['ActNo'],
            'boolKeepActive'                         => (($srcRow['KeepActive' ] == 'Yes')? '1': '0'),
            'dateInactiveAfter'                      => null,
            'idApprovalStatus'                       => $idLookupTableBySrcColumnName['ApprovalStatus'],
            'activityTitle'                          => $srcRow['ActTitle'],
            'idStaff_kitbLead'                       => $idStaffBySrcColumnName['KITBLead'     ],
            'idStaff_leadTeamManager'                => $idStaffBySrcColumnName['LeadTeamMgr'  ],
            'idClient_sponsor'                       => $idLookupTableBySrcColumnName['ClientSponsor' ],
            'idKitbOutput'                           => $idLookupTableBySrcColumnName['KITBOutput'    ],
            'idLifeCycleStage'                       => $idLookupTableBySrcColumnName['LifeCycleStage'],
            'idMisCostCentre'                        => $idLookupTableBySrcColumnName['MISCostCentre' ],
            'dateDue'                                => (($srcRow['DueDate'    ] == '')? null: $srcRow['DueDate'    ]),
            'dateRequest'                            => (($srcRow['RequestDate'] == '')? null: $srcRow['RequestDate']),
            'idPriorityLevel'                        => self::ID_PRIORITY_LEVEL_LOWEST,
            'concept_idStaff_clientRep'              => $idStaffBySrcColumnName['ClientRep'],
            'concept_description'                    => $srcRow['LongDescription'],
            'concept_dpiContext'                     => $srcRow['DPIContext'     ],
            'concept_rationale'                      => $srcRow['Rationale'      ],
            'concept_dateStart'                      => (($srcRow['StartDate'] == '')? null: $srcRow['StartDate']),
            'concept_dateFinish'                     => (($srcRow['EndDate'  ] == '')? null: $srcRow['EndDate'  ]),
            'planning_approach'                      => $srcRow['Approach'       ],
            'planning_deliverables'                  => $srcRow['Deliverables'   ],
            'planning_milestones'                    => $srcRow['Milestones'     ],
            'planning_issues'                        => $srcRow['Issues'         ],
            'planning_boolCommunicationPlanRequired' => (($srcRow['CommunicationPlan'] == '')? '0': '1'),
            'planning_communicationPlan'             => $srcRow['CommunicationPlan'],
            'planning_risks'                         => $srcRow['Risks'          ],
            'planning_estimatedCost'                 => $srcRow['EstCost'        ],
            'planning_feeForService'                 => $srcRow['Fee4Service'    ],
            'planning_kitbFunding'                   => $srcRow['KITBFunding'    ],
            'planning_edmsLink'                      => $srcRow['EDMSLink'       ],
            'authorisation_byClient_idStaff'         => $idStaffBySrcColumnName['AuthClient' ],
            'authorisation_byClient_date'            => (($srcRow['AuthClientDate' ] == '')? null: $srcRow['AuthClientDate' ]),
            'authorisation_byKitbRep_idStaff'        => $idStaffBySrcColumnName['AuthKITBRep'],
            'authorisation_byKitbRep_date'           => (($srcRow['AuthKITBRepDate'] == '')? null: $srcRow['AuthKITBRepDate']),
            'authorisation_boolKitiSubcommitee'      => (($srcRow['KITISubCtee'    ] == '')? '0': '1'),
            'authorisation_dateKitiMeeting'          => null,
            'authorisation_idKitiWorkplan'           => $idLookupTableBySrcColumnName['KITIWorkplanID'],
            'authorisation_idLifeCycleStage_kiti'    => $idLookupTableBySrcColumnName['KITILifeCycle' ],
            'review_postCompletionTwoMonth'          => $srcRow['PostImpl2Mth' ],
            'review_postCompletionTwelveMonth'       => $srcRow['PostImpl12Mth'],
            'review_idStaff_reviewerTwoMonth'        => null,
            'review_idStaff_reviewerTwelveMonth'     => null
        );

        $n_rowsAffected = 0;

        if (!self::_rowExistsInTable($db, 'activity', $row)) {
            $n_rowsAffected = $db->insert('activity', $row);
        }

        return (($n_rowsAffected == 0)? null: $db->lastInsertId());
    }

    /*
     *
     */
    private static function _getIdLookupTableBySrcColumnName($db, $srcRow)
    {
        $lookupTableIdBySrcColumnName = array();

        foreach (self::$_lookupTableNameBySrcColumnName as $srcColumnName => $lookupTableName) {
            $name = $srcRow[$srcColumnName];

            $lookupTableIdBySrcColumnName[$srcColumnName] = (
                ($name == '')? null:
                self::_getIdLookupTableMatchingName($db, $lookupTableName, $name)
            );
        }

        return $lookupTableIdBySrcColumnName;
    }

    /*
     *
     */
    private static function _getSqlDateBySrcColumnName($db, $srcRow)
    {
        $sqlDateBySrcColumnName = array();

        foreach (self::$_dateSrcColumnNames as $srcColumnName) {
            $sqlDateBySrcColumnName[$srcColumnName] = $srcRow[$srcColumnName];
        }

        return $sqlDateBySrcColumnName;
    }

    /*
     *
     */
    private static function _getIdStaffBySrcColumnName($db, $srcRow)
    {
        $idStaffBySrcColumnName = array();

        foreach (self::$_idStaffSrcColumnNames as $srcColumnName) {
            $staffNameFull = $srcRow[$srcColumnName];
            $idStaff       = self::_getIdStaffMatchingNameFull($db, $staffNameFull, $srcColumnName);

            $idStaffBySrcColumnName[$srcColumnName] = $idStaff;
        }

        return $idStaffBySrcColumnName;
    }

    /*
     *
     */
    private static function _getIdStaffMatchingNameFull($db, $nameFull, $srcColumnName)
    {
        if ($nameFull == '') {
            return null;
        }

        $rows = $db->fetchAll(
            'SELECT `identifier`
             FROM `staff`
             WHERE CONCAT(`preferred_name`, " ", `last_name`)=' . $db->quote($nameFull)
        );

        if (count($rows) == 0) {
            //throw new Exception("No staff row found matching name full '$nameFull'.");
            //echo "No staff row found for $srcColumnName '$nameFull'.\n";
            self::$_staffNotFoundNamesFullAsKeys["$srcColumnName: $nameFull"] = null;
            return null;
        }

        return $rows[0]['identifier'];
    }

    // Activity link table functions. --------------------------------------------------------//

    /*
     *
     */
    private static function _importActivityLinkTableRowsForRow($db, $idActivity, $srcRow)
    {
        foreach (self::$_activityLinkTableInfoBySrcColumnName as $srcColumnName => $linkTableInfo) {
            $namesList = $srcRow[$srcColumnName];
            $names     = explode(',', $namesList);

            foreach ($names as $name) {

                if ($name == '') {
                    continue;
                }

                $linkTableName             = $linkTableInfo['linkTableName'            ];
                $linkedTableName           = $linkTableInfo['linkedTableName'          ];
                $linkedTableLinkColumnName = $linkTableInfo['linkedTableLinkColumnName'];

                $idLinkedTable = (
                    ($linkTableName == 'link_activity_staff')?
                    self::_getIdStaffMatchingNameFull($db, $name, $srcColumnName):
                    self::_getIdLookupTableMatchingName($db, $linkedTableName, $name)
                );

                if ($idLinkedTable === null) {
                    continue;
                }

                $row = array(
                    'idActivity'               => $idActivity,
                    $linkedTableLinkColumnName => $idLinkedTable
                );

                if (!self::_rowExistsInTable($db, $linkTableName, $row)) {
                    $db->insert($linkTableName, $row);
                }
            }
        }
    }

    // Private variables. //////////////////////////////////////////////////////////////////////

    private static $_lookupTableNameBySrcColumnName = array(
        'ApprovalStatus' => 'approvalStatus',
        'ClientSponsor'  => 'client'        ,
        'KITBOutput'     => 'kitbOutput'    ,
        'KITIWorkplanID' => 'kitiWorkplan'  ,
        'LifeCycleStage' => 'lifeCycleStage',
        'KITILifeCycle'  => 'lifeCycleStage',
        'MISCostCentre'  => 'misCostCentre'
    );

    private static $_idStaffSrcColumnNames = array(
        'KITBLead'   ,
        'LeadTeamMgr',
        'ClientRep'  ,
        'AuthClient' ,
        'AuthKITBRep'
    );

    private static $_dateSrcColumnNames = array(
        'DueDate'       ,
        'RequestDate'   ,
        'StartDate'     ,
        'EndDate'       ,
        'AuthClientDate',
        'AuthKITBRepDate'
    );

    private static $_namesOfColumnsToSplitOnCommas = array(
        'KITBSuppliers'    ,
        'StaffResourceList',
        'OtherStakeholders',
        'Tasks'
    );

    private static $_activityLinkTableInfoBySrcColumnName = array(
        'KITBSuppliers'     => array(
            'linkTableName'             => 'link_activity_kitbSupplier',
            'linkedTableName'           => 'kitbSupplier'              ,
            'linkedTableLinkColumnName' => 'idKitbSupplier'
        ),
        'StaffResourcesList' => array(
            'linkTableName'             => 'link_activity_staff',
            'linkedTableName'           => 'staff'              ,
            'linkedTableLinkColumnName' => 'idStaff'
        ),
        'OtherStakeholders' => array(
            'linkTableName'             => 'link_activity_stakeholder',
            'linkedTableName'           => 'stakeholder'              ,
            'linkedTableLinkColumnName' => 'idStakeholder'
        )
    );

    private static $_staffNotFoundNamesFullAsKeys = null;

    // Class constants. /////////////////////////////////////////////////////////////////////////

    const ID_STAFF_TOM             = 23002815;
    const ID_PRIORITY_LEVEL_LOWEST = 1;
}
