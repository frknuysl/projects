#include <linux/unistd.h>
#include <errno.h>
extern int errno;
_syscall1(void, sched_det, int, flag);
