#include "gc.h"

std_gc_init();

custom_gc_init(cus);

int main(){
	std_gc_begin();
	custom_gc_begin(cus);
	struct gc_info* gif;
	struct gc_info* bif;
	int i=10;
	gc_init(10);
	cus_init(10);
	while(--i){
		gif = gc_alloc(sizeof(*gif));
		bif = cus_alloc(sizeof(*bif));
		printf("%x %d %d %x\n",(int)gif,__gc_info.gc_count,__gc_info.gc_size,(int)__gc_info.gc_base[0]);
		printf("%x %d %d %x\n",(int)bif,__cus_info.gc_count,__cus_info.gc_size,(int)__cus_info.gc_base[0]);
		gc_free(gif);
		cus_free(bif);
	}
	return 0;
}
