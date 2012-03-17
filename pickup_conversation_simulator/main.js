/*
 * vim: ts=3 sw=3 et wrap co=100 go-=b
 */

try
{
   var myself  = new SelfSimulator();
   var female  = new FemaleSimulator();
   var success = myself.converseWithPotentialMate(female);

   if (success)
   {
   }
   else
   {
      
   }
}
catch (Exception e)
{
   console.error(e);
}
