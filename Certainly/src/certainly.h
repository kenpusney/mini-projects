
#ifndef __CERTAINLY
#define __CERTAINLY
 
 /*Looping & Iteration*/
#define repeat do
#define until(exp) while(!(exp))
 
 /*Condition*/
#define unless(exp) if(!(exp))
#define or ||
#define and &&
#define not !

 
 /*Jumping*/
#define redo contiune
#define last break
#define jump goto
#define next continue

 
 /*Condition*/
#define cond if
#define when else if
#define elsif else if
#define except else
 
#endif /*__CERTAINLY_H*/