#include <stdio.h>
#include <stdlib.h>
#include <string.h>

#include "gc.h"

int __gc_init(size_t size,struct gc_info* info){
	void* addr;
	if( addr = malloc(size*sizeof(void*)) ){
		memset(addr,0,size*sizeof(void*));
		info->gc_base = (void**)addr;
		info->gc_size = size;
		info->gc_count = 0;
	}
	return (int)addr;
}

void* __gc_alloc(size_t size,struct gc_info* info){
	void* addr;
	int pos = 0;
	if(info->gc_count >= info->gc_size){
		addr = null;
	}else if ( addr = malloc(size) ){
		if(!info->gc_base[info->gc_count]){
			pos = info->gc_count;
		}else{
			while(info->gc_base[pos]) ++pos;
		}
		info->gc_base[pos] = addr;
		++info->gc_count;
	}
	return addr;
}

int __gc_free(void* addr,struct gc_info* info){
	int i=0;
	if( info->gc_base[info->gc_count-1] == addr && info->gc_count > 0){
		info->gc_base[info->gc_count-1] = null;
		free(addr);
		--info->gc_count;
	}else{
		for(i = 0; i< info->gc_size && info->gc_count > 0; ++i){
			if( info->gc_base[i] == addr){
				if(i != info->gc_count-1){
					info->gc_base[i] = info->gc_base[info->gc_count-1];
					i = info->gc_count-1;
				}
				info->gc_base[i] = null;
				free(addr);
				--info->gc_count;
				break;
			}
		}
	}
	return (i >= info->gc_size) && ! addr;
}


void* gc_destory(){
	int i,sum=0;
	for(i=0;i< __gc_info.gc_size; ++i){
		if (__gc_info.gc_base[i]){
			free(__gc_info.gc_base[i]);
			__gc_info.gc_base[i] = null;
		}
	}
}

