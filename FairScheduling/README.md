CSE331 Phase 2: Custom Linux System Call – sched_det
=======================================================

📌 Overview
-----------
This project introduces a custom system call named `sched_det` into the Linux kernel, enabling extended process scheduling features for analysis or educational purposes. It includes modifications across kernel-space, user-space headers, and includes a user-space test program to demonstrate functionality.

📂 Project Structure
---------------------
CSE331p2/
├── KERNEL_SPACE/
│   ├── arch/i386/kernel/entry.S                # System call entry point registration
│   ├── fs/sched_det.c                          # Implementation of the custom system call
│   ├── fs/Makefile                             # Modified to compile sched_det.c
│   ├── include/asm-i386/unistd.h               # System call number definition
│   ├── include/linux/sched_det.h               # Kernel header for sched_det
│   ├── include/linux/sched.h                   # May include related process struct updates
│   ├── kernel/fork.c                           # Possibly updated for process creation logic
│   ├── kernel/sched.c                          # Main scheduler logic (optional modifications)
│
├── USER_SPACE/
│   ├── asm/unistd.h                            # User-space syscall number definition
│   ├── linux/sched_det.h                       # User-space accessible syscall prototype
│
├── home/
│   ├── loopfunction.c                          # Helper/test logic to run loops
│   ├── phase2.c                                # Main user-space test program

⚙️ Features
----------
- Adds a new system call: `sched_det(pid_t pid)`
- Custom kernel behavior for process scheduling or tracing
- User-space testing and validation with `phase2.c`

🛠️ How to Use
--------------
1. Patch the Kernel

Copy the modified kernel files into the corresponding directories in the Linux source tree. Recompile the kernel:

    make menuconfig
    make -j$(nproc)
    make modules_install
    make install
    reboot

Note: Make sure to back up your existing kernel and grub configuration.

2. Rebuild the User Program

    gcc -o phase2 home/phase2.c home/loopfunction.c
    sudo ./phase2

3. Verify

Observe terminal output for correct behavior of `sched_det`.

🧪 Example Output
------------------
Calling sched_det with PID: 1234
Returned: 0
...

🧠 Notes
---------
- Tested on kernel version 2.6.x or 3.x (based on header paths).
- Modifications are meant for educational use and not recommended on production systems.

👨‍💻 Author
------------
Furkan Uysal 
