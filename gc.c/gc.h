
#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#ifndef __GC_H__
#define __GC_H__

#ifndef null
#define null (void*)(0)
#endif //null

struct gc_info{
	void** gc_base;
	int gc_count;
	int gc_size;
};


extern struct gc_info __gc_info;

int __gc_init(size_t size,struct gc_info* info);
void* __gc_alloc(size_t size,struct gc_info* info);
int __gc_free(void* addr,struct gc_info* info);

void* gc_destory();

#define gc_free(ptr) __gc_free(ptr,&__gc_info)
#define gc_alloc(size) __gc_alloc(size,&__gc_info)
#define gc_init(size) __gc_init(size,&__gc_info)

#define std_gc_init() struct gc_info __gc_info
#define std_gc_begin() atexit(gc_destory) 

#define custom_gc_init(cus) struct gc_info __##cus##_info; \
	int cus##_free(void* ptr) { return __gc_free(ptr, &__##cus##_info); } \
	void* cus##_alloc(size_t size) { return __gc_alloc(size, &__##cus##_info); } \
	int cus##_init(size_t size) { return __gc_init(size, &__##cus##_info); } \
	void* cus##_destory(){  \
		int i,sum=0;  \
		for(i=0;i< __##cus##_info.gc_size; ++i){  \
			if (__##cus##_info.gc_base[i]){  \
				free(__##cus##_info.gc_base[i]);  \
				__##cus##_info.gc_base[i] = null;  \
			}  \
		}  \
	}
	
#define custom_gc_begin(cus) atexit(cus##_destory)
 
#endif //__GC_H__
