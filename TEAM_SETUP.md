# 🚀 TEAM SETUP GUIDE - Getting Started

## Prerequisites for All Teams

### Required Software
- PHP 8.4+
- Composer
- Node.js 18+
- npm or yarn
- MySQL/MariaDB
- Git

### Project Setup (Do This First)

```bash
# Clone repository
git clone <repo-url>
cd quanlysinhvien-main

# Install PHP dependencies
composer install

# Install Node dependencies
npm install

# Copy environment file
cp .env.example .env

# Generate app key
php artisan key:generate

# Setup database (see below for DB setup)
php artisan migrate --seed

# Run tests
php artisan test

# Build assets
npm run build
```

---

## 👨‍💻 TEAM 1: BACKEND DEVELOPER SETUP

### Your Role
Build all backend logic, models, controllers, and business logic.

### Key Tools & Commands

**Development Server:**
```bash
# Option 1: PHP built-in server
php -S localhost:9000 -t public

# Option 2: Laravel Artisan (recommended)
php artisan serve

# Option 3: XAMPP/LAMP
# Access via http://localhost/quanlysinhvien-main/public
```

**Database Setup:**
```bash
# Create database
mysql -u root -p < create_db.php

# Or manually:
# CREATE DATABASE quanlysinhvien;
# CREATE USER 'studentmgr'@'localhost' IDENTIFIED BY 'password';
# GRANT ALL PRIVILEGES ON quanlysinhvien.* TO 'studentmgr'@'localhost';

# Run migrations
php artisan migrate

# Seed test data
php artisan db:seed
```

**Code Generation (Artisan Commands):**
```bash
# Generate new controller
php artisan make:controller Admin/FacultyController

# Generate model with migration
php artisan make:model Faculty -m

# Generate observer
php artisan make:observer GradeObserver --model=Grade

# Generate mail class
php artisan make:mail AcademicWarningMail

# Generate service provider
php artisan make:provider GradeServiceProvider

# Generate test
php artisan make:test Feature/GradeControllerTest
```

**Testing:**
```bash
# Run all tests
php artisan test

# Run specific test class
php artisan test tests/Feature/GradeControllerTest.php

# Run with coverage
php artisan test --coverage

# Watch mode (re-run on file changes)
php artisan test --watch
```

### Project Structure (Your Area)

```
app/
├── Http/
│   └── Controllers/
│       ├── Admin/
│       │   ├── FacultyController.php
│       │   ├── MajorController.php
│       │   ├── ClassController.php
│       │   ├── CourseController.php
│       │   ├── CourseClassController.php
│       │   ├── StudentController.php
│       │   ├── GradeController.php
│       │   ├── StatisticsController.php
│       │   └── DashboardController.php
│       └── Lecturer/
│           └── GradeController.php
├── Models/
│   ├── Faculty.php
│   ├── Major.php
│   ├── Classes.php
│   ├── Student.php
│   ├── Lecturer.php
│   ├── Course.php
│   ├── CourseClass.php
│   ├── Grade.php
│   └── ...
├── Mail/
│   ├── AcademicWarningMail.php
│   ├── CourseWarningMail.php
│   ├── ExamBanMail.php
│   └── AccountCreated.php
├── Imports/ & Exports/
├── Observers/
├── Services/ & Traits/
└── Http/Requests/ (validation)

database/
├── migrations/ (23 files)
├── seeders/
└── factories/

tests/
├── Feature/ (integration tests)
└── Unit/ (unit tests)

config/
├── app.php
├── database.php
├── mail.php
└── grades.php (you create)
```

### Database Schema Overview

**Core Tables:**
- `users` - Authentication
- `faculties` - Department
- `majors` - Program
- `classes` - Class sections
- `students` - Student records
- `lecturers` - Lecturer records
- `courses` - Course definitions
- `course_classes` - Course + class combos
- `grades` - Student grades
- `notifications` - Email/system notifications
- `activities` - Audit logs
- `attendances` - Attendance records
- `attendance_sessions` - Session definitions
- `course_evaluations` - Student evaluations
- `chat_logs` - Chat history

**Relationships:**
```
Faculty 1-* Major 1-* Class *-* Student
Faculty 1-* Course *-* CourseClass *-* Lecturer
CourseClass *-* Student (via course_class_student)
Student 1-* Grade *-1 Course
```

### Coding Standards

**Model Naming:**
```php
// ✅ CORRECT
class Faculty extends Model { }       // Singular, PascalCase
class StudentObserver { }

// ❌ WRONG
class Faculties extends Model { }     // Don't pluralize
```

**Controller Naming:**
```php
// ✅ CORRECT
class FacultyController extends Controller { }
class Admin\FacultyController extends Controller { }

// ❌ WRONG
class AdminFacultyController { }      // Use namespace instead
```

**Method Naming:**
```php
// ✅ CORRECT - follow Laravel conventions
public function index() { }           // List
public function create() { }          // Show form
public function store() { }           // Save
public function show() { }            // Display one
public function edit() { }            // Edit form
public function update() { }          // Save update
public function destroy() { }         // Delete

// Business logic methods
public function calculateGPA() { }    // camelCase
public function sendWarningEmail() { }
```

**Database Queries:**
```php
// ✅ CORRECT - Use eager loading
$students = Student::with(['class', 'major', 'faculty'])->get();

// ❌ WRONG - N+1 query problem
foreach ($students as $student) {
    echo $student->class->name;  // Lazy loads for each student!
}
```

### Testing Template

```php
<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\Student;

class StudentControllerTest extends TestCase
{
    public function test_can_list_students()
    {
        // Arrange
        Student::factory(3)->create();
        
        // Act
        $response = $this->get('/admin/students');
        
        // Assert
        $response->assertStatus(200);
        $response->assertSeeText('Students');
    }
    
    public function test_can_create_student()
    {
        $response = $this->post('/admin/students', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);
        
        $this->assertDatabaseHas('students', [
            'email' => 'john@example.com'
        ]);
    }
}
```

### Common Issues & Solutions

**Issue:** "No such file or directory" 404 errors
```bash
# Solution: Use correct document root
php artisan serve        # ✅ Correct
php -S localhost:9000    # ❌ Wrong - use -t public flag
php -S localhost:9000 -t public  # ✅ Correct
```

**Issue:** Database connection errors
```bash
# Check .env file
cat .env | grep DB_

# Test connection
php artisan tinker
>>> DB::connection()->getPdo()

# Re-migrate
php artisan migrate:fresh --seed
```

**Issue:** Models not found
```bash
# Clear autoloader cache
composer dump-autoload

# Check namespace
namespace App\Models;   // ✅ Must be correct
```

### Key Files to Review

1. `database/migrations/` - Understand the schema
2. `app/Models/User.php` - See existing model structure
3. `app/Http/Controllers/Auth/` - See existing controllers
4. `routes/web.php` - Understand routing
5. `config/app.php` - App configuration

---

## 👨‍💻 TEAM 2: FEATURES DEVELOPER SETUP

### Your Role
Build attendance, evaluations, notifications, and activity logging features.

### Key Tools & Commands

**Same as Team 1, plus:**

**Generate Attendance Models:**
```bash
php artisan make:model Attendance -m
php artisan make:model AttendanceSession -m
php artisan make:controller Lecturer/AttendanceController
php artisan make:controller Admin/AttendanceController
```

**Generate Notification Models:**
```bash
php artisan make:model Notification -m
php artisan make:notification GeneralNotification
php artisan make:listener NotificationListener
```

**QR Code Package:**
```bash
# Already installed: simplesoftwareio/simple-qr-code
# Usage in code:
use QrCode;
$qrCode = QrCode::size(300)->generate('attendance-session-123');
```

### Project Structure (Your Area)

```
app/
├── Http/
│   └── Controllers/
│       ├── Admin/
│       │   ├── AttendanceController.php
│       │   ├── NotificationController.php
│       │   ├── ActivityController.php
│       │   └── EvaluationController.php
│       └── Lecturer/
│           ├── AttendanceController.php
│           ├── NotificationController.php
│           └── EvaluationController.php
├── Models/
│   ├── Attendance.php
│   ├── AttendanceSession.php
│   ├── Notification.php
│   ├── Activity.php
│   ├── CourseEvaluation.php
│   └── ChatLog.php
├── Services/
│   ├── ExamBanService.php
│   ├── NotificationService.php
│   └── AttendanceService.php
├── Jobs/ (for async email)
├── Listeners/ (for event handling)
└── Events/ (if needed)

database/
└── migrations/
    ├── create_attendances_table.php
    ├── create_attendance_sessions_table.php
    ├── create_notifications_table.php
    ├── create_activities_table.php
    ├── create_course_evaluations_table.php
    └── create_chat_logs_table.php
```

### Database Tables (Your Area)

**Attendance Schema:**
```sql
-- attendance_sessions
├── id
├── course_class_id → course_classes
├── session_date
├── start_time
├── end_time
├── qr_code (data or path)
└── status (active, closed, cancelled)

-- attendances
├── id
├── session_id → attendance_sessions
├── student_id → students
├── status (present, absent, late, excused)
├── check_in_time
└── checked_in_by
```

**Notification Schema:**
```sql
-- notifications
├── id
├── student_id → students
├── type (academic_warning, exam_ban, course_warning, general)
├── title
├── message
├── data (JSON)
├── read_at
└── created_at
```

**Activity Schema:**
```sql
-- activities
├── id
├── user_id → users
├── action (created, updated, deleted)
├── model_type (App\Models\Grade)
├── model_id
├── changes (JSON - old/new values)
└── created_at
```

### Key Business Logic

**Exam Ban Service:**
```php
class ExamBanService {
    public function checkAndBanStudent(Student $student) {
        $absenceCount = $student->attendances()
            ->where('status', 'absent')
            ->count();
        
        if ($absenceCount >= config('attendance.ban_threshold')) {
            // Create notification
            Notification::create([
                'student_id' => $student->id,
                'type' => 'exam_ban',
                'message' => 'You are banned from exams due to excessive absences'
            ]);
            
            // Send email
            Mail::to($student->user->email)->send(new ExamBanMail($student));
            
            $student->update(['exam_banned' => true]);
        }
    }
}
```

**Notification Service:**
```php
class NotificationService {
    public function notify(Student $student, $type, $message) {
        // Save to database
        Notification::create([
            'student_id' => $student->id,
            'type' => $type,
            'message' => $message
        ]);
        
        // Queue email if enabled
        if ($student->notification_preferences['email']) {
            Mail::queue(new NotificationMail($student, $message));
        }
    }
}
```

### Coding Pattern: Activity Observer

```php
namespace App\Observers;

use App\Models\Grade;
use App\Models\Activity;

class GradeObserver {
    public function created(Grade $grade) {
        Activity::create([
            'user_id' => auth()->id(),
            'action' => 'created',
            'model_type' => Grade::class,
            'model_id' => $grade->id,
            'changes' => ['new_values' => $grade->toArray()]
        ]);
    }
    
    public function updated(Grade $grade) {
        Activity::create([
            'user_id' => auth()->id(),
            'action' => 'updated',
            'model_type' => Grade::class,
            'model_id' => $grade->id,
            'changes' => [
                'old' => $grade->getOriginal(),
                'new' => $grade->getDirty()
            ]
        ]);
    }
}
```

### Testing Attendance

```php
public function test_can_create_attendance_session() {
    $courseClass = CourseClass::factory()->create();
    
    $response = $this->post('/lecturer/attendance', [
        'course_class_id' => $courseClass->id,
        'session_date' => now()->date(),
        'start_time' => '09:00',
        'end_time' => '10:30',
    ]);
    
    $this->assertDatabaseHas('attendance_sessions', [
        'course_class_id' => $courseClass->id
    ]);
}

public function test_can_mark_student_present() {
    $attendance = Attendance::factory()->create(['status' => 'absent']);
    
    $response = $this->put("/lecturer/attendance/{$attendance->id}", [
        'status' => 'present'
    ]);
    
    $this->assertTrue($attendance->refresh()->status === 'present');
}
```

---

## 👨‍💻 TEAM 3: FRONTEND DEVELOPER SETUP

### Your Role
Build all user-facing views, forms, and UI components using Blade & Tailwind CSS.

### Key Tools & Commands

**Development Server:**
```bash
# Terminal 1: PHP Server
php artisan serve

# Terminal 2: Vite dev server (watches CSS/JS changes)
npm run dev

# For production build:
npm run build
```

**Tailwind CSS Development:**
```bash
# Already configured in tailwind.config.js
# Modify tailwind.config.js for custom colors, fonts, etc.

# Build CSS
npm run build

# Watch CSS changes
npm run dev
```

### Project Structure (Your Area)

```
resources/
├── views/
│   ├── layouts/
│   │   ├── app.blade.php         (main layout)
│   │   ├── admin.blade.php       (admin wrapper)
│   │   ├── lecturer.blade.php    (lecturer wrapper)
│   │   └── student.blade.php     (student wrapper)
│   ├── components/
│   │   ├── navbar.blade.php
│   │   ├── sidebar.blade.php
│   │   ├── alerts.blade.php
│   │   ├── form-input.blade.php
│   │   └── pagination.blade.php
│   ├── admin/
│   │   ├── dashboard.blade.php
│   │   ├── faculty/
│   │   ├── major/
│   │   ├── class/
│   │   ├── course/
│   │   ├── student/
│   │   ├── grade/
│   │   ├── attendance/
│   │   ├── evaluation/
│   │   ├── notification/
│   │   └── activity/
│   ├── lecturer/
│   │   ├── dashboard.blade.php
│   │   ├── grade/
│   │   ├── attendance/
│   │   └── evaluation/
│   ├── student/
│   │   ├── dashboard.blade.php
│   │   ├── grade/
│   │   ├── attendance/
│   │   └── evaluation/
│   ├── auth/
│   │   ├── login.blade.php
│   │   ├── register.blade.php
│   │   └── forgot-password.blade.php
│   └── errors/
│       ├── 404.blade.php
│       └── 500.blade.php
├── css/
│   └── app.css (custom styles)
├── js/
│   └── app.js (interactive features)
└── images/

public/
└── build/ (generated CSS/JS from Vite)
```

### Blade Syntax Cheatsheet

```blade
<!-- Variables -->
{{ $variable }}                          <!-- Echo & escape -->
{!! $html !!}                            <!-- Raw HTML (unsafe) -->

<!-- Control Flow -->
@if ($condition)
    <p>True</p>
@elseif ($other)
    <p>Other</p>
@else
    <p>False</p>
@endif

<!-- Loops -->
@foreach ($items as $item)
    <div>{{ $item->name }}</div>
@endforeach

@forelse ($items as $item)
    <div>{{ $item->name }}</div>
@empty
    <p>No items</p>
@endforelse

<!-- Forms -->
<form method="POST" action="{{ route('store') }}">
    @csrf
    @method('PUT')
    <input type="text" name="name" />
    @error('name')<span class="error">{{ $message }}</span>@enderror
</form>

<!-- Includes -->
@include('components.navbar')
@include('admin.header', ['title' => 'Users'])

<!-- Inheritance -->
@extends('layouts.app')
@section('content')
    <h1>Welcome</h1>
@endsection

<!-- Comments -->
{{-- This is a Blade comment --}}
```

### Tailwind CSS Basics

```html
<!-- Layout -->
<div class="flex justify-between items-center">        <!-- Flexbox -->
<div class="grid grid-cols-3 gap-4">                  <!-- Grid -->
<div class="container mx-auto px-4">                 <!-- Container -->

<!-- Sizing -->
<div class="w-full h-64 bg-blue-500">                <!-- Width & Height -->
<div class="text-lg font-bold text-gray-800">        <!-- Text -->

<!-- Spacing -->
<div class="p-4 m-2 mb-8">                           <!-- Padding & Margin -->

<!-- Colors -->
<button class="bg-blue-600 hover:bg-blue-700 text-white"> <!-- Background & Hover -->

<!-- Responsive -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3"> <!-- Mobile-first -->

<!-- Forms -->
<input class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500" />
<button class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
```

### Form Component Template

```blade
<!-- resources/views/admin/faculty/create.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Create Faculty</h1>
    
    <form method="POST" action="{{ route('faculty.store') }}" class="bg-white p-6 rounded-lg shadow">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
            <input 
                type="text" 
                id="name" 
                name="name" 
                value="{{ old('name') }}"
                class="mt-1 px-3 py-2 w-full border border-gray-300 rounded-lg @error('name') border-red-500 @enderror"
            />
            @error('name')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
            @enderror
        </div>
        
        <div class="mb-4">
            <label for="code" class="block text-sm font-medium text-gray-700">Code</label>
            <input type="text" id="code" name="code" value="{{ old('code') }}" class="mt-1 px-3 py-2 w-full border border-gray-300 rounded-lg" />
        </div>
        
        <div class="flex gap-2">
            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                Create
            </button>
            <a href="{{ route('faculty.index') }}" class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
```

### Table Component Template

```blade
<!-- resources/views/admin/faculty/index.blade.php -->
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-3xl font-bold">Faculties</h1>
        <a href="{{ route('faculty.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
            Add Faculty
        </a>
    </div>
    
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <table class="w-full">
            <thead class="bg-gray-100 border-b">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Name</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Code</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold">Majors</th>
                    <th class="px-4 py-3 text-right text-sm font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($faculties as $faculty)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-4 py-3">{{ $faculty->name }}</td>
                        <td class="px-4 py-3">{{ $faculty->code }}</td>
                        <td class="px-4 py-3">{{ $faculty->majors->count() }}</td>
                        <td class="px-4 py-3 text-right">
                            <a href="{{ route('faculty.edit', $faculty) }}" class="text-blue-600 hover:text-blue-800 text-sm">Edit</a>
                            <form method="POST" action="{{ route('faculty.destroy', $faculty) }}" class="inline" onsubmit="return confirm('Delete?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800 text-sm ml-2">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="px-4 py-3 text-center text-gray-500">No faculties found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    <!-- Pagination -->
    {{ $faculties->links() }}
</div>
@endsection
```

### Dashboard Template

```blade
@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-8">Admin Dashboard</h1>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Total Students</p>
            <p class="text-4xl font-bold text-blue-600">{{ $stats['total_students'] ?? 0 }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Active Courses</p>
            <p class="text-4xl font-bold text-green-600">{{ $stats['active_courses'] ?? 0 }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">Avg GPA</p>
            <p class="text-4xl font-bold text-yellow-600">{{ number_format($stats['avg_gpa'] ?? 0, 2) }}</p>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <p class="text-gray-600 text-sm">At Risk</p>
            <p class="text-4xl font-bold text-red-600">{{ $stats['at_risk_students'] ?? 0 }}</p>
        </div>
    </div>
    
    <!-- Charts or Lists can go here -->
</div>
@endsection
```

### Common Issues & Solutions

**Issue:** CSS not updating (Vite not watching)
```bash
# Solution: Make sure dev server is running
npm run dev  # Keep this terminal open
# Then save your CSS/JS files
```

**Issue:** Images not loading
```blade
<!-- ✅ CORRECT -->
<img src="{{ asset('images/logo.png') }}" />

<!-- ❌ WRONG -->
<img src="/images/logo.png" />
<img src="images/logo.png" />
```

**Issue:** CSRF token error
```blade
<!-- ✅ CORRECT - Add to all forms -->
<form method="POST">
    @csrf
    ...
</form>

<!-- ❌ WRONG - Forgot @csrf -->
<form method="POST">
    ...
</form>
```

**Issue:** $errors not available
```blade
<!-- ✅ CORRECT - After form submission -->
@error('name')
    {{ $message }}
@enderror

<!-- Only works if controller returns back()->withErrors() or form validation fails -->
```

### Testing Frontend

```php
public function test_faculty_index_page_loads() {
    $this->get('/admin/faculty')
        ->assertStatus(200)
        ->assertSeeText('Faculties')
        ->assertSeeText('Add Faculty');
}

public function test_can_submit_faculty_form() {
    $response = $this->post('/admin/faculty', [
        'name' => 'Engineering',
        'code' => 'ENG'
    ]);
    
    $response->assertRedirect('/admin/faculty');
    $this->assertDatabaseHas('faculties', ['name' => 'Engineering']);
}
```

### Performance Tips

1. **Lazy load images:**
```blade
<img src="{{ asset('image.png') }}" loading="lazy" />
```

2. **Minimize CSS in production:**
```bash
npm run build  # Creates minified CSS
```

3. **Use pagination:**
```blade
{{ $items->links() }}  <!-- Splits data into pages -->
```

4. **Cache DB queries in dashboard:**
```php
$stats = Cache::remember('dashboard_stats', 60, function() {
    return [...expensive queries...];
});
```

---

## 🤝 Collaboration Guidelines

### Git Workflow

```bash
# Create feature branch
git checkout -b feature/faculty-crud

# Make changes
git add .
git commit -m "feat: add faculty CRUD"

# Push to GitHub
git push origin feature/faculty-crud

# Create Pull Request (PR)
# - Request review from team
# - Make sure tests pass
# - Address feedback
# - Merge when approved
```

### Code Review Checklist

Before approving PR, check:
- [ ] Code follows project conventions
- [ ] All tests pass (`php artisan test`)
- [ ] No console errors/warnings
- [ ] Database migrations are reversible
- [ ] No hardcoded values (use config/env)
- [ ] Security: No SQL injection, XSS, etc.
- [ ] Performance: No N+1 queries

### Commit Message Convention

```
feat: add student import feature
fix: resolve grade calculation bug
refactor: simplify attendance logic
docs: update README
test: add tests for GPA calculation
chore: update dependencies
```

---

## 📱 Testing During Development

**Manual Testing Checklist:**
- [ ] Forms submit correctly
- [ ] Validation shows error messages
- [ ] Success messages display
- [ ] Redirects work as expected
- [ ] Database updates properly
- [ ] Emails send (check Mailtrap if using)
- [ ] Mobile responsive
- [ ] No JS console errors

**Browser Testing:**
- Chrome/Edge (Chromium)
- Firefox
- Safari (if on Mac)
- Mobile browsers

---

## 🆘 Getting Help

**Common Channels:**
1. Google/Stack Overflow for general Laravel issues
2. Laravel docs: https://laravel.com/docs/11
3. Team Slack channel for project-specific questions
4. Daily standup: 10 AM Monday-Friday

**Common Resources:**
- Laravel Pest docs: https://pestphp.com
- Tailwind CSS docs: https://tailwindcss.com
- Blade templating: https://laravel.com/docs/11/blade
- Database/Model: https://laravel.com/docs/11/eloquent

---

**Good luck! 🚀 You've got this!**
