# 📖 PROJECT REFERENCE GUIDE

## Quick Links

| Document | Purpose |
|----------|---------|
| **structure.txt** | Complete project file tree |
| **task_division.md** | Detailed task breakdown for 3 teams (this file) |
| **TEAM_SETUP.md** | Setup instructions for each team |
| **README.md** | Original project documentation |
| **tailieu.txt** | Project notes/documentation |

---

## 🏗️ Project Architecture

### MVC Pattern

```
HTTP Request
    ↓
routes/web.php (routing)
    ↓
Controllers (app/Http/Controllers/)
    ↓
Models (app/Models/) + Business Logic
    ↓
Database (MySQL)
    ↓
Models + Observers (logging, email triggers)
    ↓
Mail/Notifications (app/Mail/)
    ↓
Views (resources/views/) [Blade Templates]
    ↓
HTTP Response + Tailwind CSS
```

### Role-Based Access Control (RBAC)

```
3 Roles:
├── Admin (SuperUser)
│   ├── Access: All features
│   ├── Routes: /admin/*
│   └── Views: resources/views/admin/
├── Lecturer (Teacher)
│   ├── Access: Their classes, grades, attendance
│   ├── Routes: /lecturer/*
│   └── Views: resources/views/lecturer/
└── Student (Learner)
    ├── Access: Their own grades, schedule, evaluations
    ├── Routes: /student/*
    └── Views: resources/views/student/
```

**Middleware Protection:**
```php
// In routes/web.php
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    // Only admin can access
});

Route::middleware(['auth', 'lecturer'])->prefix('lecturer')->group(function () {
    // Only lecturer can access
});
```

---

## 🗄️ Database Relationships Map

### Entity Relationship Diagram (Text)

```
┌─────────────────────────────────────────────────────────────┐
│                    ORGANIZATIONAL STRUCTURE                  │
├─────────────────────────────────────────────────────────────┤
│                                                              │
│  Faculty (1) ────1:* → Major ──1:* → Class                 │
│    |                                    |                   │
│    |                                    *:1 → Student (1)   │
│    └──────────────────────────────────────────────────────┘ │
│                                                              │
│  Faculty (1) ────1:* → Course ──1:* → CourseClass          │
│                                           |                 │
│                                           *:* → Student     │
│                                           |                 │
│                                           *:1 → Lecturer    │
│                                                              │
│  CourseClass (1) ────1:* → Attendance                       │
│                                |                            │
│                                *:1 → AttendanceSession      │
│                                                              │
│  Student (1) ────1:* → Grade ──*:1 → Course                │
│                    |                                        │
│                    └──→ Triggers Observer                   │
│                           │                                 │
│                           └──→ Send Email/Create Notif      │
│                                                              │
│  User (1) ────1:1 → Student/Lecturer/Admin                  │
│                                                              │
└─────────────────────────────────────────────────────────────┘
```

### Key Relationships in Code

```php
// Faculty relationships
Faculty::with('majors')->get();              // Get all majors in faculty
Faculty::with('courses')->get();             // Get all courses from faculty

// Class relationships
Class::with('students', 'faculty', 'major')->get();

// Course relationships
Course::with('courseClasses', 'evaluations')->get();

// Student relationships
Student::with('class', 'faculty', 'major', 'grades')->get();

// Grade relationships
Grade::with('student', 'course', 'courseClass')->get();

// Attendance relationships
Attendance::with('session', 'student', 'courseClass')->get();
```

---

## 📊 Key Models & Methods

### Student Model

**Scopes (filters):**
```php
Student::atRisk()->get();              // GPA < 2.0
Student::onProbation()->get();         // GPA 2.0-2.5
Student::excellent()->get();           // GPA > 3.5
Student::bySemester('Spring2026')->get();
```

**Methods:**
```php
$student->calculateGPA();              // Compute GPA
$student->getLetterGrade();            // Get letter grade
$student->hasAttendanceProblem();      // Check absences
$student->isExamBanned();              // Check if banned
```

### Grade Model

**Calculation:**
```php
GPA = SUM(grade_point × credit_hours) / SUM(credit_hours)

Letter Grades:
A = 90-100   → 4.0 points
B = 80-89    → 3.0 points
C = 70-79    → 2.0 points
D = 60-69    → 1.0 points
F = <60      → 0.0 points
```

**Observer Triggers:**
```php
// When grade saved/updated:
GradeObserver→created()
    ├── Log to activities table
    ├── Recalculate student GPA
    ├── Check academic standing
    └── Send warning emails if needed
```

### Attendance Model

**Status Options:**
```php
PRESENT = Scanned on time
LATE = Scanned after 15 minutes
ABSENT = No scan
EXCUSED = Manual approval
```

**Exam Ban Logic:**
```php
If ABSENT_COUNT >= 3:
    └── Send ExamBanMail
    └── Create notification
    └── Set exam_banned = true
```

---

## 📧 Email Templates

| Template | Trigger | Recipient | Purpose |
|----------|---------|-----------|---------|
| **AcademicWarningMail** | GPA < 2.0 | Student | Alert about probation |
| **CourseWarningMail** | Course GPA < 2.0 | Student | Alert about course failure risk |
| **ExamBanMail** | Absent > threshold | Student | Notify exam ban |
| **AccountCreated** | New user signup | Student/Lecturer | Welcome email |

**Configuration:**
- Driver: SMTP (or Mailgun, SendGrid)
- Queue: Redis/database (async sending)
- Test: Use Mailtrap for development

---

## 🔐 Security Considerations

### Authentication & Authorization

```php
// Middleware (in app/Http/Middleware/)
class CheckRole {
    public function handle($request, $next, $role) {
        if (auth()->user()->role !== $role) {
            abort(403, 'Unauthorized');
        }
        return $next($request);
    }
}

// Usage in routes
Route::post('/admin/grades', [GradeController::class, 'store'])
    ->middleware('auth', 'role:admin');
```

### Common Vulnerabilities to Avoid

1. **SQL Injection** — Always use Eloquent/parameter binding
   ```php
   // ✅ SAFE
   Student::where('id', $id)->first();
   
   // ❌ UNSAFE
   Student::whereRaw("id = $id")->first();
   ```

2. **XSS (Cross-Site Scripting)** — Always escape output
   ```blade
   <!-- ✅ SAFE - Auto-escaped -->
   {{ $student->name }}
   
   <!-- ❌ UNSAFE - Raw output -->
   {!! $student->name !!}
   ```

3. **CSRF** — Always include @csrf in forms
   ```blade
   <form method="POST">
       @csrf  <!-- ✅ REQUIRED -->
       ...
   </form>
   ```

4. **Mass Assignment** — Use $fillable/$guarded
   ```php
   class Student extends Model {
       protected $fillable = ['name', 'email', 'student_id'];
       // Only these fields can be set via mass assignment
   }
   ```

---

## 🧪 Testing Structure

```
tests/
├── Pest.php                    // Configuration
├── TestCase.php               // Base class
├── Feature/                   // End-to-end tests
│   ├── StudentControllerTest.php
│   ├── GradeControllerTest.php
│   └── AttendanceControllerTest.php
└── Unit/                      // Component tests
    ├── GradeCalculationTest.php
    ├── ExamBanServiceTest.php
    └── NotificationServiceTest.php
```

**Test Pattern (Pest/PHPUnit):**
```php
// Arrange → Act → Assert
public function test_can_create_grade() {
    $student = Student::factory()->create();
    
    $response = $this->post('/admin/grades', [
        'student_id' => $student->id,
        'value' => 85
    ]);
    
    $this->assertDatabaseHas('grades', ['value' => 85]);
}
```

---

## 📈 Performance Optimization Tips

### 1. Eager Loading (Prevent N+1 queries)

```php
// ❌ BAD - N+1 queries
$students = Student::all();
foreach ($students as $student) {
    echo $student->class->name;  // Queries database for each student!
}

// ✅ GOOD - 1 query
$students = Student::with('class')->get();
foreach ($students as $student) {
    echo $student->class->name;  // Already loaded
}
```

### 2. Pagination (Handle large datasets)

```php
// Endpoint
public function index() {
    $students = Student::paginate(15);  // 15 per page
    return view('admin.student.index', compact('students'));
}

// View
{{ $students->links() }}  // Renders pagination controls
```

### 3. Caching (Reduce DB hits)

```php
use Illuminate\Support\Facades\Cache;

$stats = Cache::remember('dashboard_stats', 3600, function() {
    return [
        'total_students' => Student::count(),
        'avg_gpa' => Student::average('gpa')
    ];
});
```

### 4. Queuing (Async tasks)

```php
// Queue email instead of sending immediately
Mail::queue(new AcademicWarningMail($student));

// Process queue: php artisan queue:work
```

### 5. Indexing (Database optimization)

```php
// In migration:
Schema::create('students', function (Blueprint $table) {
    $table->id();
    $table->string('email')->unique();      // Index
    $table->foreignId('class_id')->index(); // Index
    $table->timestamps();
});
```

---

## 🐛 Common Debugging Techniques

### 1. Laravel Tinker (REPL)

```bash
php artisan tinker

# Query students
>>> $students = Student::all();
>>> $students->count();

# Test model methods
>>> $student = Student::first();
>>> $student->calculateGPA();
```

### 2. Log Files

```bash
# Check logs
tail -f storage/logs/laravel.log

# Log in code
\Log::info('Processing student', ['id' => $student->id]);
\Log::error('Grade calculation failed', $exception);
```

### 3. Database Debugging

```bash
# Enable query logging
DB::enableQueryLog();
$students = Student::with('class')->get();
dd(DB::getQueryLog());  // Shows all queries executed
```

### 4. Blade Debugging

```blade
<!-- Print variable -->
@dd($student)

<!-- Check if isset -->
@isset($variable)
    {{ $variable }}
@endisset

<!-- Dump and continue -->
{{ dump($student) }}
```

---

## 📋 Environment Configuration

**Critical .env variables:**

```ini
# Application
APP_NAME="Student Management"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=quanlysinhvien
DB_USERNAME=root
DB_PASSWORD=

# Mail
MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=your_username
MAIL_PASSWORD=your_password
MAIL_ENCRYPTION=tls

# Queue
QUEUE_CONNECTION=database

# Cache
CACHE_DRIVER=file
```

---

## 🚀 Deployment Checklist

Before going live:

```bash
# 1. Verify environment
php artisan env

# 2. Run tests
php artisan test

# 3. Build assets
npm run build

# 4. Migrate database
php artisan migrate --force

# 5. Seed if needed
php artisan db:seed --class=ProductionSeeder

# 6. Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Clear old cache
php artisan cache:clear

# 8. Start queue worker
php artisan queue:work &

# 9. Setup supervisor for queue (production)
# Edit /etc/supervisor/conf.d/laravel.conf
```

---

## 📞 Getting Help

### Documentation

- **Laravel Docs:** https://laravel.com/docs/11
- **Pest Testing:** https://pestphp.com
- **Tailwind CSS:** https://tailwindcss.com
- **Blade Templates:** https://laravel.com/docs/11/blade
- **Eloquent ORM:** https://laravel.com/docs/11/eloquent

### Within Team

- **Code Review:** Create PR for feedback
- **Questions:** Slack #development channel
- **Bugs:** Create GitHub issue with details

### Resources

- Stack Overflow: Tag `laravel`
- Laravel Laracasts: Video tutorials
- GitHub Issues: Search existing solutions

---

## ✅ Final Checklist Before Starting

- [ ] PHP 8.4+ installed
- [ ] Composer installed
- [ ] Node.js 18+ installed
- [ ] MySQL running
- [ ] Git configured
- [ ] Project cloned locally
- [ ] `.env` file created
- [ ] Database created
- [ ] Migrations run
- [ ] Test data seeded
- [ ] Dev server works (`php artisan serve`)
- [ ] Vite dev server works (`npm run dev`)

**Ready to code? 🚀 Let's go!**

---

**Last Updated:** May 5, 2026  
**Version:** 1.0
