# 📋 TASK DIVISION - QUẢN LÝ SINH VIÊN PROJECT
**Project:** Student Management System (Laravel 11 + Tailwind CSS)  
**Duration:** 8-10 weeks  
**Team Size:** 3 developers  
**Date Created:** May 5, 2026

---

## 📊 Overview: 3 Teams by Feature Domain

```
┌──────────────────────────────────────────────────────────────────┐
│                    FEATURE-BASED TEAM DIVISION                    │
├──────────────────────────────────────────────────────────────────┤
│                                                                    │
│  Team 1 (40-45%)          Team 2 (30-35%)       Team 3 (25-30%)  │
│  Backend Logic            Complex Features     Frontend UI        │
│  ─────────────            ──────────────       ──────────────    │
│  • CRUD Backends          • Attendance         • All Views        │
│  • Database Logic         • Evaluations        • Forms & UI       │
│  • Grades/GPA             • Notifications      • Dashboards       │
│  • Email Services         • Activity Logs      • Reports          │
│  • Import/Export          • Admin Panel        • Responsive CSS   │
│                                                                    │
└──────────────────────────────────────────────────────────────────┘
```

---

## 👨‍💻 TEAM 1: BACKEND & BUSINESS LOGIC DEVELOPER (40-45%)

### Team 1 Lead Responsibilities

**Core Objective:** Build robust backend APIs and business logic for all core entities and features.

### Sprint Breakdown

#### **Sprint 1-2 (Weeks 1-2): Database Foundation & Core Models**

**Deliverables:**
- ✅ Database migrations finalized (23 migrations)
- ✅ All models created with relationships
- ✅ Database seeders for test data
- ✅ Factory definitions for testing

**Files to Work On:**
```
database/
├── migrations/ (all 23 files)
├── factories/UserFactory.php
├── seeders/ (all seeder files)

app/Models/
├── Faculty.php
├── Major.php
├── Classes.php
├── Student.php
├── Lecturer.php
├── User.php
├── Course.php
├── CourseClass.php
├── Grade.php
└── Others...
```

**Tasks:**
- [ ] Review and finalize all 23 migration files
- [ ] Create model relationships (belongsTo, hasMany, belongsToMany)
- [ ] Setup scopes and query builders
- [ ] Create database seeders for Faculty, Major, Classes
- [ ] Create test factories

**Blockers:** None (start here first)

---

#### **Sprint 3-4 (Weeks 3-4): Core CRUD - Faculty, Major, Classes, Courses**

**Deliverables:**
- ✅ Full CRUD endpoints for Faculty, Major, Classes
- ✅ Course & CourseClass management
- ✅ Validation requests for all entities
- ✅ Authorization checks

**Files to Work On:**
```
app/Http/Controllers/Admin/
├── FacultyController.php
├── MajorController.php
├── ClassController.php
├── CourseController.php
└── CourseClassController.php

app/Http/Requests/
├── FacultyRequest.php
├── MajorRequest.php
├── ClassRequest.php
├── CourseRequest.php
└── CourseClassRequest.php

app/Models/
├── Faculty.php (relationships)
├── Major.php
├── Classes.php
├── Course.php
└── CourseClass.php
```

**Tasks:**
- [ ] Build FacultyController (index, create, store, edit, update, delete)
- [ ] Build MajorController (CRUD + relationships to Faculty)
- [ ] Build ClassController (CRUD + assign students)
- [ ] Build CourseController (CRUD + assign to major)
- [ ] Build CourseClassController (assign lecturer + students)
- [ ] Create form request validation classes
- [ ] Add authorization middleware

**Collaboration:** Coordinate with Team 3 on form data structure  
**Testing:** Write Pest tests for each controller

---

#### **Sprint 5-6 (Weeks 5-6): Students, Grades & GPA System**

**Deliverables:**
- ✅ Student CRUD + bulk operations
- ✅ Grade management system
- ✅ GPA calculation engine
- ✅ Letter grade assignment
- ✅ Academic warning logic
- ✅ Grade locking system

**Files to Work On:**
```
app/Http/Controllers/Admin/
├── StudentController.php
└── GradeController.php

app/Http/Controllers/Lecturer/
└── GradeController.php

app/Models/
├── Student.php (scopes: at-risk, on-probation)
├── Grade.php
└── Observers/GradeObserver.php

app/Imports/
├── StudentsImport.php
└── GradesImport.php

app/Exports/
├── StudentsExport.php
└── GradesExport.php

app/Traits/ (create if needed)
└── GradeCalculationTrait.php

config/
└── grades.php (GPA thresholds, letter grade scale)
```

**Tasks:**
- [ ] Build StudentController (CRUD + bulk student management)
- [ ] Implement Student model scopes (at-risk students, probation list)
- [ ] Build GradeController for Admin & Lecturer
- [ ] Create Grade model with relationships
- [ ] Build GPA calculation logic (trait or service)
  - GPA = average of all grades with weight by credit hours
  - Support multiple grading scales (4.0, 10.0)
- [ ] Implement letter grade assignment (A, B, C, D, F based on GPA)
- [ ] Create GradeObserver to trigger academic warnings on grade save
- [ ] Build Students/Grades import using Maatwebsite Excel
- [ ] Build Students/Grades export
- [ ] Create grade locking system (prevent editing locked grades)

**Key Business Logic:**
```php
// GPA Calculation Example
GPA = SUM(grade_point * credit_hours) / SUM(credit_hours)

// Letter Grade Scale
A: >= 85,  B: 75-84,  C: 65-74,  D: 55-64,  F: < 55

// Academic Warning Trigger
if (GPA < 2.0) → Send AcademicWarningMail
if (Course_GPA < 2.0) → Send CourseWarningMail
```

**Collaboration:** Team 2 will need grade data for statistics  
**Testing:** Unit tests for GPA calculation, integration tests for imports

---

#### **Sprint 6-7 (Weeks 6-7): Mail Services & Observers**

**Deliverables:**
- ✅ 4 mail templates implemented
- ✅ Email queue system
- ✅ GradeObserver working
- ✅ Notification triggers

**Files to Work On:**
```
app/Mail/
├── AcademicWarningMail.php (GPA warning)
├── CourseWarningMail.php (course risk)
├── ExamBanMail.php (attendance ban)
└── AccountCreated.php (new user welcome)

app/Observers/
└── GradeObserver.php

config/
└── mail.php (SMTP setup)

database/
└── migrations/ (jobs table for queue)
```

**Tasks:**
- [ ] Configure mail driver (SMTP or Mailgun)
- [ ] Create AcademicWarningMail template
- [ ] Create CourseWarningMail template
- [ ] Create ExamBanMail template
- [ ] Create AccountCreated template
- [ ] Implement GradeObserver (listens to Grade::created and Grade::updated)
- [ ] Setup job queue for async email sending
- [ ] Test all mail templates

**Mail Content Examples:**
```
Subject: Academic Warning - GPA Below 2.0
Body: "Dear [Student Name], your GPA has dropped below 2.0. 
You are at risk of academic probation..."

Subject: Course Warning - Grade Critical
Body: "You have received a grade below 2.0 in [Course]. 
Please meet with your lecturer..."
```

**Collaboration:** Integrate with Team 2's notification system  
**Testing:** Fake email testing in Pest

---

#### **Sprint 7-8 (Weeks 7-8): Statistics & Dashboard Backend**

**Deliverables:**
- ✅ Dashboard statistics queries
- ✅ Risk analysis engine
- ✅ Report data APIs
- ✅ Performance optimization

**Files to Work On:**
```
app/Http/Controllers/Admin/
├── StatisticsController.php
└── DashboardController.php

app/Http/Controllers/Lecturer/
├── StatisticsController.php
└── DashboardController.php

app/Services/ (create if needed)
├── StatisticsService.php
└── RiskAnalysisService.php

app/Models/
└── Add scopes for statistics queries
```

**Tasks:**
- [ ] Build Admin dashboard (total students, courses, at-risk count)
- [ ] Build Lecturer dashboard (my classes, grades to enter, attendance)
- [ ] Build risk analysis queries:
  - Students with GPA < 2.0
  - Students with poor attendance
  - Students with course failures
- [ ] Create statistics exports (CSV/PDF)
- [ ] Optimize queries with eager loading
- [ ] Add caching for heavy queries

**Statistics to Display:**
```
Dashboard Stats:
- Total Students: SELECT COUNT(*) FROM students
- Active Classes: SELECT COUNT(*) FROM classes WHERE active=1
- At-Risk Students: SELECT COUNT(*) FROM students WHERE gpa < 2.0
- Avg GPA by Faculty: GROUP BY faculty, AVG(gpa)
```

**Testing:** Query optimization tests, performance benchmarks

---

### Team 1 File Checklist

**Controllers (6):**
- [ ] FacultyController
- [ ] MajorController
- [ ] ClassController
- [ ] CourseController
- [ ] CourseClassController
- [ ] StudentController
- [ ] GradeController (Admin)
- [ ] StatisticsController (Admin)
- [ ] DashboardController (Admin)

**Models (10+):**
- [ ] Faculty, Major, Classes, Student, Lecturer, User
- [ ] Course, CourseClass, Grade
- [ ] All relationships configured

**Services/Traits:**
- [ ] GradeCalculationTrait
- [ ] StatisticsService
- [ ] RiskAnalysisService

**Mail Classes (4):**
- [ ] AcademicWarningMail
- [ ] CourseWarningMail
- [ ] ExamBanMail
- [ ] AccountCreated

**Import/Export (4):**
- [ ] StudentsImport
- [ ] StudentsExport
- [ ] GradesImport
- [ ] GradesExport

**Tests:**
- [ ] tests/Unit/GradeCalculationTest.php
- [ ] tests/Feature/StudentControllerTest.php
- [ ] tests/Feature/GradeControllerTest.php

---

### Team 1 Dependencies & Collaboration

**Blocks Team 2 & 3:**
- Week 1: Database schema must be locked
- Week 2: Models + relationships finalized
- Week 3: Controllers basic structure ready
- Week 4+: APIs stable for frontend integration

**Requires from Team 3:**
- Form field names and validation rules

---

## 👨‍💻 TEAM 2: ATTENDANCE & COMPLEX FEATURES (30-35%)

### Team 2 Lead Responsibilities

**Core Objective:** Build specialized features: attendance system, course evaluations, notifications, and activity logs.

### Sprint Breakdown

#### **Sprint 1-2 (Weeks 1-2): Setup & Attendance Foundation**

**Deliverables:**
- ✅ Attendance session model & controller
- ✅ QR code generation system
- ✅ Database structure for attendance tracking

**Files to Work On:**
```
app/Http/Controllers/Admin/
└── AttendanceController.php

app/Http/Controllers/Lecturer/
└── AttendanceController.php

app/Models/
├── AttendanceSession.php
└── Attendance.php

database/migrations/
└── attendance related migrations (Team 1 will create)

config/
└── attendance.php (settings)
```

**Tasks:**
- [ ] Understand database schema from Team 1
- [ ] Create AttendanceSession model (date, time, course_class_id, status)
- [ ] Create Attendance model (student_id, session_id, status, time)
- [ ] Build AttendanceController for Lecturer:
  - index (show sessions)
  - create (new session)
  - store (save session)
  - show (session details with attendance list)
- [ ] Build Admin AttendanceController (view all, analytics)
- [ ] Implement QR code generation (use `simplesoftwareio/simple-qr-code`)
- [ ] Add attendance status options: Present, Absent, Late, Excused

**Business Logic:**
```php
// Attendance Session Creation
- Generate unique QR code
- Set active time window (start_time, end_time)
- Students scan QR to mark present
- Auto-mark absent after deadline

// Attendance Status Rules
- Scanned within time = PRESENT
- Scanned late (15+ min) = LATE
- No scan = ABSENT
- Manual approval = EXCUSED
```

**Collaboration:** Coordinate attendance data format with Team 1 (Grade warnings)

---

#### **Sprint 3-4 (Weeks 3-4): Exam Ban System & Notifications**

**Deliverables:**
- ✅ Exam ban auto-trigger logic
- ✅ Notification system (database + email)
- ✅ Admin notification management

**Files to Work On:**
```
app/Http/Controllers/Admin/
├── NotificationController.php
└── ExamBanController.php

app/Models/
├── Notification.php
└── NotificationLog.php (track sent emails)

app/Services/
└── ExamBanService.php

database/migrations/
└── notifications table (Team 1 creates)
```

**Tasks:**
- [ ] Create Notification model with relationships to Student
- [ ] Implement exam ban logic:
  - If absent_count > threshold (e.g., 3) → ban
  - Store ban record in notifications table
- [ ] Build NotificationController:
  - Display notifications to admin
  - Send exam ban emails (via Team 1's ExamBanMail)
  - Mark notifications as read
  - Delete old notifications
- [ ] Create NotificationService for sending
- [ ] Add notification preferences (email on/off)
- [ ] Setup notification queue jobs

**Business Logic:**
```php
// Exam Ban Rules
if (student.absent_count >= ABSENCE_THRESHOLD) {
    create_exam_ban_notification();
    send_exam_ban_email();
    mark_student_banned_for_exams = true;
}

// Notification Routing
- Save to database
- Queue email sending
- Log to activity (Team 2)
```

---

#### **Sprint 5 (Week 5): Activity Logging**

**Deliverables:**
- ✅ Activity audit log system
- ✅ Admin activity dashboard
- ✅ Comprehensive logging middleware

**Files to Work On:**
```
app/Http/Controllers/Admin/
└── ActivityController.php

app/Models/
└── Activity.php

app/Observers/
├── StudentObserver.php
├── GradeObserver.php
└── CourseObserver.php

database/migrations/
└── activities table
```

**Tasks:**
- [ ] Create Activity model (user_id, action, model_type, model_id, changes)
- [ ] Build ActivityController:
  - index (show all activities)
  - filter by user, model type, date
  - show activity details with before/after values
- [ ] Create observers for:
  - StudentObserver (log create, update, delete)
  - GradeObserver extension (log grade changes)
  - Other important models
- [ ] Implement change tracking (store old vs new values)
- [ ] Add activity report views

**Activity Log Format:**
```php
[2026-05-05 15:30:45]
User: admin@example.com
Action: Student Grade Updated
Model: Grade #1234
Changes: 
  - old: "85"
  - new: "90"
```

---

#### **Sprint 6 (Week 6): Course Evaluations**

**Deliverables:**
- ✅ Evaluation form creation
- ✅ Student evaluation submission
- ✅ Results analysis

**Files to Work On:**
```
app/Http/Controllers/Admin/
└── EvaluationController.php

app/Http/Controllers/Student/
└── EvaluationController.php

app/Models/
└── CourseEvaluation.php

resources/views/
├── admin/evaluation/
└── student/evaluation/
```

**Tasks:**
- [ ] Create CourseEvaluation model
- [ ] Build Admin EvaluationController:
  - index (list courses)
  - create/edit evaluation form template
  - store evaluation template
  - show (view evaluation results)
  - analytics (charts, avg scores)
- [ ] Build Student EvaluationController:
  - list (my evaluations to submit)
  - show (evaluation form)
  - store (submit evaluation)
- [ ] Implement evaluation questions:
  - Teaching quality (1-5)
  - Course organization (1-5)
  - Lecturer communication (1-5)
  - Course difficulty (1-5)
  - Overall satisfaction (1-5)
- [ ] Add comment field
- [ ] Create results dashboard with stats

**Evaluation Form Template:**
```
CourseEvaluation
├── course_class_id
├── questions: JSON array
├── avg_score: calculated
├── submission_count: count
└── period: semester/year
```

---

#### **Sprint 7-8 (Weeks 7-8): Admin Dashboard & Integration**

**Deliverables:**
- ✅ Unified admin panel
- ✅ All features integrated
- ✅ Reporting dashboards

**Files to Work On:**
```
app/Http/Controllers/Admin/
├── DashboardController.php
├── NotificationController.php
└── ActivityController.php

resources/views/admin/
├── dashboard.blade.php
├── notifications/
└── activities/
```

**Tasks:**
- [ ] Build Admin Dashboard page:
  - Quick stats (attendance, evaluations, notifications)
  - Recent activities feed
  - Pending notifications
  - Upcoming attendance sessions
- [ ] Build Notifications management page:
  - List all notifications
  - Send manual notifications
  - View notification logs
- [ ] Build Activity logs page:
  - Filter by user, model, date
  - Show detailed changes
- [ ] Add export to CSV/PDF for reports
- [ ] Performance optimization (caching)

---

### Team 2 File Checklist

**Controllers (7):**
- [ ] Admin/AttendanceController
- [ ] Lecturer/AttendanceController
- [ ] Admin/NotificationController
- [ ] Admin/ActivityController
- [ ] Admin/EvaluationController
- [ ] Student/EvaluationController
- [ ] (Admin/DashboardController - shared with Team 1)

**Models (5):**
- [ ] Attendance
- [ ] AttendanceSession
- [ ] Notification
- [ ] Activity
- [ ] CourseEvaluation

**Services:**
- [ ] ExamBanService
- [ ] NotificationService

**Tests:**
- [ ] tests/Feature/AttendanceControllerTest.php
- [ ] tests/Feature/NotificationControllerTest.php
- [ ] tests/Feature/EvaluationControllerTest.php
- [ ] tests/Unit/ExamBanServiceTest.php

---

### Team 2 Dependencies & Collaboration

**Depends on Team 1:**
- Grade data (for exam ban)
- Student model (for notifications)
- Course classes (for attendance sessions)

**Provides to Team 3:**
- Attendance data structure
- Notification UI requirements
- Evaluation form structure
- Activity log format

---

## 👨‍💻 TEAM 3: FRONTEND & UI DEVELOPER (25-30%)

### Team 3 Lead Responsibilities

**Core Objective:** Create all user-facing views, forms, dashboards using Blade templates and Tailwind CSS.

### Sprint Breakdown

#### **Sprint 1-2 (Weeks 1-2): Layout & Base Components**

**Deliverables:**
- ✅ Master layout template
- ✅ Navigation components
- ✅ Base CSS/Tailwind setup
- ✅ Authentication pages

**Files to Work On:**
```
resources/views/
├── layouts/
│   ├── app.blade.php (main layout)
│   ├── admin.blade.php
│   ├── lecturer.blade.php
│   └── student.blade.php
├── auth/
│   ├── login.blade.php
│   ├── register.blade.php
│   └── password-reset.blade.php
└── components/
    ├── navbar.blade.php
    ├── sidebar.blade.php
    └── alerts.blade.php

resources/css/
├── app.css
└── components.css

resources/js/
└── app.js

tailwind.config.js (configuration)
```

**Tasks:**
- [ ] Review Tailwind CSS config from template
- [ ] Create main app layout (header, nav, sidebar, footer, main content area)
- [ ] Create role-based layouts (Admin, Lecturer, Student variants)
- [ ] Design responsive navigation bar with role detection
- [ ] Create sidebar navigation for each role
- [ ] Build reusable alert/notification components
- [ ] Style authentication pages (login, register, password reset)
- [ ] Setup form component styles (input, select, checkbox, radio)
- [ ] Create button variants (primary, secondary, danger)
- [ ] Test responsiveness on mobile/tablet/desktop

**Layout Structure:**
```html
<app.blade.php>
  <navbar role="{admin|lecturer|student}"> 
    <sidebar items="{role_menu}">
      <main @yield('content')>
```

**Collaboration:** Ask Team 1 & 2 for their required form fields

---

#### **Sprint 2-3 (Weeks 2-3): CRUD Forms & Tables**

**Deliverables:**
- ✅ Data entry forms (Faculty, Major, Classes, Courses, Students)
- ✅ Data listing tables with sorting/filtering
- ✅ Pagination components

**Files to Work On:**
```
resources/views/admin/
├── faculty/
│   ├── index.blade.php (list)
│   ├── create.blade.php
│   └── edit.blade.php
├── major/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── class/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php (with student list)
├── course/
│   ├── index.blade.php
│   ├── create.blade.php
│   └── edit.blade.php
├── student/
│   ├── index.blade.php
│   ├── create.blade.php
│   ├── edit.blade.php
│   └── show.blade.php (profile)
└── courseclass/
    ├── index.blade.php
    ├── create.blade.php
    └── edit.blade.php
```

**Tasks:**
- [ ] Build Faculty CRUD forms (create, edit, delete buttons)
- [ ] Build Major CRUD forms
- [ ] Build Classes CRUD forms
- [ ] Build Courses CRUD forms
- [ ] Build CourseClass assignment forms (select lecturer, select students)
- [ ] Build Student CRUD forms with validation feedback
- [ ] Create data tables with:
  - Sorting by column headers
  - Pagination (showing X-Y of Z records)
  - Search/filter boxes
  - Bulk actions (select multiple, delete, export)
  - Edit/View/Delete action buttons
- [ ] Add form validation error display
- [ ] Create success/error toast notifications
- [ ] Implement confirm dialogs for destructive actions

**Form Pattern:**
```html
<form method="POST" action="{{ $editMode ? route('update', $item->id) : route('store') }}">
  @csrf
  @method($editMode ? 'PUT' : 'POST')
  
  <div class="form-group">
    <label for="name">Name</label>
    <input type="text" name="name" value="{{ old('name', $item->name ?? '') }}" />
    @error('name')<span class="error">{{ $message }}</span>@enderror
  </div>
  
  <button type="submit">{{ $editMode ? 'Update' : 'Create' }}</button>
</form>
```

---

#### **Sprint 4 (Week 4): Grades & Student Views**

**Deliverables:**
- ✅ Grade entry forms (Lecturer)
- ✅ Grade view pages (Student, Lecturer, Admin)
- ✅ GPA display components

**Files to Work On:**
```
resources/views/lecturer/
├── grade/
│   ├── index.blade.php (list my courses)
│   ├── edit.blade.php (bulk grade entry)
│   └── show.blade.php (course grades)

resources/views/student/
├── dashboard.blade.php (show my GPA, grades)
└── grade/
    └── index.blade.php (transcript)

resources/views/admin/
├── grade/
│   ├── index.blade.php (all grades)
│   ├── show.blade.php (course breakdown)
│   └── report.blade.php (statistics)
```

**Tasks:**
- [ ] Build Lecturer grade entry form (spreadsheet-like grid):
  - Student name | Current grade | New grade | Status
  - Bulk save all grades
  - Validate before save
- [ ] Build grade view page (show course GPA)
- [ ] Build Student transcript page:
  - List all courses with grades, credits, GPA
  - Display overall GPA prominently
  - Color-code letter grades (A=green, F=red)
- [ ] Add GPA meter/progress bar visualization
- [ ] Show warnings if GPA < 2.0
- [ ] Create grade export button (PDF transcript)
- [ ] Add filter by semester/year

**Grade Display Component:**
```html
<div class="grade-card">
  <h3>{{ $course->name }}</h3>
  <p>Grade: <span class="grade-{{ $grade->letter }}">{{ $grade->letter }}</span></p>
  <p>Points: {{ $grade->point }}/4.0</p>
  <p>Credits: {{ $course->credit_hours }}</p>
</div>
```

---

#### **Sprint 5 (Week 5): Attendance & Evaluation Views**

**Deliverables:**
- ✅ Attendance check-in page
- ✅ Attendance session management
- ✅ Evaluation forms

**Files to Work On:**
```
resources/views/lecturer/
├── attendance/
│   ├── index.blade.php (my sessions)
│   ├── create.blade.php (new session)
│   ├── show.blade.php (session details with attendance list)
│   └── edit.blade.php (mark attendance manually)

resources/views/student/
└── attendance/
    ├── index.blade.php (my attendance)
    └── checkin.blade.php (QR scanner)

resources/views/admin/
└── evaluation/
    ├── index.blade.php (list)
    ├── create.blade.php (design form)
    ├── show.blade.php (results/analytics)
    └── report.blade.php

resources/views/student/
└── evaluation/
    ├── index.blade.php (my evaluations)
    └── show.blade.php (evaluation form)
```

**Tasks:**
- [ ] Build Lecturer attendance session creation form
- [ ] Build attendance session details page with student list:
  - Show student name, status (Present/Absent/Late/Excused)
  - Option to manually change status
  - Button to generate/display QR code
- [ ] Build Student QR check-in page:
  - Camera input for QR scanner
  - Submit status
  - Show confirmation (success/error)
- [ ] Build Lecturer attendance history page
- [ ] Build evaluation form creation page (add/remove questions)
- [ ] Build evaluation submission form (radio/score inputs)
- [ ] Build evaluation results page with:
  - Average scores
  - Charts/graphs of responses
  - Written comments section
- [ ] Add evaluation export to PDF

---

#### **Sprint 6-7 (Weeks 6-7): Dashboards & Reports**

**Deliverables:**
- ✅ Admin dashboard
- ✅ Lecturer dashboard
- ✅ Student dashboard
- ✅ Report pages

**Files to Work On:**
```
resources/views/admin/
├── dashboard.blade.php (main stats)
├── statistics/
│   ├── students.blade.php (student analytics)
│   ├── grades.blade.php (grade distribution)
│   ├── attendance.blade.php (attendance trends)
│   └── risk.blade.php (at-risk students)

resources/views/lecturer/
└── dashboard.blade.php (my classes, pending grades, attendance)

resources/views/student/
└── dashboard.blade.php (my schedule, recent grades, announcements)

resources/views/shared/
├── reports/
│   ├── gpa_distribution.blade.php
│   ├── attendance_report.blade.php
│   └── evaluation_summary.blade.php
```

**Tasks:**
- [ ] Build Admin Dashboard:
  - Key metrics cards (total students, courses, avg GPA)
  - Chart: Grade distribution (bar chart)
  - Chart: Attendance trends (line chart)
  - Table: Recent activities (last 10)
  - Table: At-risk students (GPA < 2.0)
  - Quick actions (buttons to manage data)
- [ ] Build Lecturer Dashboard:
  - My classes list (with student count)
  - Pending grade entries (courses without grades)
  - Recent attendance sessions
  - Pending evaluations
  - Quick link to attendance check-in
- [ ] Build Student Dashboard:
  - Current semester courses
  - Recent grades (last 5 courses)
  - Current GPA prominent display
  - Upcoming attendance sessions
  - Notifications/warnings
  - Evaluation forms to complete
- [ ] Build statistics pages:
  - Student analytics (enrollment trends, GPA by major)
  - Grade analytics (grade distribution, pass/fail rates)
  - Attendance analytics (attendance by class, by time)
  - Risk analysis (students below 2.0 GPA)
- [ ] Add chart library (Chart.js or Apex Charts)
- [ ] Create report export to PDF

**Dashboard Statistics Sample:**
```
┌─────────────────────────────────┐
│ ADMIN DASHBOARD                  │
├─────────────────────────────────┤
│ Students: 1,245    Courses: 89   │
│ Avg GPA: 3.2       At Risk: 45   │
├─────────────────────────────────┤
│ Recent Activities                │
│ - Grade entered: Math 101        │
│ - Student enrolled: John Doe     │
│ - Attendance session: Physics    │
└─────────────────────────────────┘
```

---

#### **Sprint 8 (Week 8): Notifications & Polish**

**Deliverables:**
- ✅ Notification display system
- ✅ Profile pages
- ✅ Chat widget UI
- ✅ Final CSS polish & responsiveness

**Files to Work On:**
```
resources/views/components/
├── notification-bell.blade.php
└── notifications-dropdown.blade.php

resources/views/shared/
├── notifications/
│   ├── index.blade.php (notification center)
│   └── show.blade.php (notification details)
└── profile/
    ├── student-profile.blade.php
    └── lecturer-profile.blade.php

resources/views/chat/
└── widget.blade.php (AI chat interface)
```

**Tasks:**
- [ ] Build notification bell icon (with unread count badge)
- [ ] Build notification dropdown menu (show last 5)
- [ ] Build notification center page:
  - List all notifications with filters
  - Mark as read/unread
  - Delete old notifications
  - Archive notifications
- [ ] Build Student profile page:
  - Display info (name, ID, major, faculty, contact)
  - Edit profile form
  - Change password form
  - View academic standing
- [ ] Build Lecturer profile page (similar)
- [ ] Build Chat widget UI:
  - Toggle button to open/close
  - Message display area
  - Input field
  - Send button
- [ ] Final responsive design checks:
  - Mobile: Test on iPhone 375px
  - Tablet: Test on iPad 768px
  - Desktop: Test on 1920px+
- [ ] Accessibility audit (WCAG 2.1)
- [ ] Performance optimization (image lazy loading, CSS minification)

---

### Team 3 File Checklist

**View Directories:**
- [ ] resources/views/layouts/ (4 main layouts)
- [ ] resources/views/admin/ (15+ pages)
- [ ] resources/views/lecturer/ (10+ pages)
- [ ] resources/views/student/ (10+ pages)
- [ ] resources/views/components/ (reusable components)

**Specific Blade Files (~40-50 total):**
- [ ] Admin: Dashboard, Faculty, Major, Class, Course, Student, Grade, Attendance, Evaluation, Notification, Activity
- [ ] Lecturer: Dashboard, Grade, Attendance, Evaluation, Notification
- [ ] Student: Dashboard, Grade, Attendance, Evaluation, Profile
- [ ] Shared: Auth pages, Profile pages, Error pages

**CSS/JS:**
- [ ] resources/css/app.css (custom styles)
- [ ] resources/js/app.js (interactive features)
- [ ] resources/js/components/ (Vue/Alpine components if needed)

**Configuration:**
- [ ] tailwind.config.js (customization)
- [ ] vite.config.js (build optimization)

**Tests:**
- [ ] tests/Feature/DashboardViewTest.php
- [ ] tests/Feature/FormValidationTest.php

---

### Team 3 Dependencies & Collaboration

**Depends on:**
- Team 1: Controller/route names, form field structure
- Team 2: Notification/attendance data structure

**Provides to:**
- UI/UX feedback to Teams 1 & 2
- Form field names for validation

---

## 📅 DEVELOPMENT TIMELINE

```
Week 1  │ [DB Setup]          [Layout & Auth]
        │ T1 starts           T3 starts
        │
Week 2  │ [Models] --------→ [Components]
        │ T1 continues        T3 continues
        │
Week 3  │ [CRUD: Faculty]    [CRUD Forms UI]
        │ T1 implements       T3 implements
        │
Week 4  │ [CRUD: Courses]    [Grades UI]      [Attendance Base]
        │ T1 continues        T3 continues     T2 starts
        │
Week 5  │ [Students & Grades] [Attendance UI] [Notifications]
        │ T1 implements       T3 continues    T2 implements
        │
Week 6  │ [GPA Calc & Warnings] [Evaluations UI] [Exam Ban & Logs]
        │ T1 implements         T3 continues    T2 implements
        │
Week 7  │ [Mail & Observers] [Dashboards]    [Integration]
        │ T1 implements     T3 continues      T2 continues
        │
Week 8  │ [Statistics]      [Polish & Responsive] [Final Testing]
        │ T1 finishes       T3 finishes         T2 finishes
        │
Week 9  │ [Integration Testing & Bug Fixes]
        │ ALL TEAMS
        │
Week 10 │ [Performance & Deployment]
        │ ALL TEAMS
```

---

## 🎯 Dependency Map

```
CRITICAL PATH:
Team 1: Database (Wk 1-2)
  ↓
Team 1: Models & Controllers (Wk 2-4)
  ↓
Team 2: Features depending on controllers (Wk 4-7)
  ↓
Team 3: Views & UI integration (parallel with 1 & 2)
  ↓
Integration Testing (Wk 8-9)
```

**Parallel Work (Can do simultaneously):**
- Week 1: T1 database, T3 layouts
- Week 2: T1 models, T3 components
- Week 3-4: T1 CRUD, T3 forms, T2 planning
- Week 5-7: All teams working on different features

**Blocking Dependencies:**
- T2 needs T1's Grade model for exam ban logic
- T3 needs T1's controller routes for form actions
- T3 needs T2's notification structure for UI

---

## 📋 Communication Protocol

### Weekly Standup (Every Monday 10:00 AM)
- Each team: 5-min update
  - What completed last week
  - What blocked
  - What starting this week

### Daily Slack Updates (5:00 PM)
- Brief status: 👍 on track | ⚠️ needs help | 🔴 blocked
- Blockers tagged to relevant team

### Code Review Process
- All PRs reviewed by another team member
- Merge only after approval + tests passing
- Use conventional commits: `feat: add grade calculation`

### Database Changes Coordination
- T1 owns database schema
- Any schema changes: notify T2 & T3 immediately
- T2 & T3 cannot modify migrations without T1 approval

---

## ✅ Definition of Done (DoD)

**For each task:**
- [ ] Code written following Laravel best practices
- [ ] Unit tests written (>80% coverage)
- [ ] Feature tests passing
- [ ] Code review approved
- [ ] No console warnings/errors
- [ ] Database migrations reversible
- [ ] Documentation updated (README/comments)
- [ ] Accessibility checked (for frontend)
- [ ] Performance tested (for complex queries)

---

## 🚀 Deployment Checklist

**Before going live:**
- [ ] All tests passing (100% suite)
- [ ] No database migration errors
- [ ] Mail configuration tested (send test email)
- [ ] File uploads tested
- [ ] PDF exports working
- [ ] QR code generation working
- [ ] Performance: page load < 2 seconds
- [ ] Security: SQL injection tests passed
- [ ] User acceptance testing (UAT) completed
- [ ] Database backup created
- [ ] Rollback plan documented

---

## 📊 Project Statistics

| Metric | Count | Owner |
|--------|-------|-------|
| Controllers | 25+ | T1 (15), T2 (7), T3 (0) |
| Models | 15+ | T1 (12), T2 (3), T3 (0) |
| Blade Views | 50+ | T3 (all) |
| Tests | 50+ | All teams |
| Database Tables | 23 | T1 |
| Mail Templates | 4 | T1 |
| Import/Export | 4 | T1 |
| Lines of Code | ~15,000-20,000 | All |

---

## 📞 Contact & Escalation

**Project Manager:** [Name/Contact]

**Team 1 Lead:** [Backend Developer]  
**Team 2 Lead:** [Features Developer]  
**Team 3 Lead:** [Frontend Developer]

**Escalation Path:**
- Daily issues → Solve in team
- Cross-team blockers → PM coordinates
- Architecture changes → All leads discuss

---

**Document Version:** 1.0  
**Last Updated:** May 5, 2026  
**Next Review:** Week 2
