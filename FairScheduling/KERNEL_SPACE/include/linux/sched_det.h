#include <linux/sched_det.h>
#include <linux/sched.h>
asmlinkage void sys_sched_det.h(int flag){
    sched_flag = flag;
}
