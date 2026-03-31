try {
$users = App\Models\User::where('role', 'student')->get();
echo "Found " . $users->count() . " students.\n";

if ($users->count() > 0) {
Illuminate\Support\Facades\Notification::send($users, new App\Notifications\GeneralNotification('Bulk Test', 'Bulk Message', 'Admin', 'Admin'));
echo "Sent bulk notification.\n";
} else {
echo "No students to send to.\n";
}

echo "Total Notifications in DB: " . DB::table('notifications')->count() . "\n";

} catch (\Exception $e) {
echo "Error: " . $e->getMessage() . "\n";
}