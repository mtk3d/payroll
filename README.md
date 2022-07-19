# Payroll

Small application built for manage employments, and generate salary reports.

## Setup local environment



## Run tests



## Event Storming session

I made short event storming session, where I built a big-picture context of an application.
I managed to find three contexts:

- `Employment` - this context is basically a catalog with departments, employees, and relation between them.
- `Salary` - in this context aggregates all data required to properly calculate salaries.
- `Report` - simple context, to keep report information, status and date of generating.

I discovered also two small processes, about creating department and employee, and one bigger, about generating salary report.
This process is going across report and salary contexts, so I need to define a process manager here.

Output of the session:
![Payroll-EventStorming](./docs/assets/Payroll-EventStorming.png)

## C4 model diagram

This is a simple representation of the system in C4 notation. I prepared first three levels.

### C1 diagram
![Payroll-C1](./docs/assets/Payroll-C1.svg)

### C2 diagram
![Payroll-C1](./docs/assets/Payroll-C2.svg)

### C3 diagram
![Payroll-C1](./docs/assets/Payroll-C3.svg)
