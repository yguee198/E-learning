<?php

namespace App\Providers;

use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event to listener mappings for the application.
     *
     * @var array<class-string, array<int, class-string>>
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        \App\Events\UserRegistered::class => [
            \App\Listeners\LogUserRegistration::class,
            \App\Listeners\SendWelcomeNotification::class,
            \App\Listeners\NotifyAdminsOfNewUser::class,
            \App\Listeners\InitializeProgressTracker::class,
        ],
        \App\Events\UserRoleChanged::class => [
            \App\Listeners\NotifyUserRoleChanged::class,
            \App\Listeners\NotifyAdminOfRoleChange::class,
        ],
        \App\Events\UserDeactivated::class => [
            \App\Listeners\NotifyUserDeactivated::class,
            \App\Listeners\NotifyAdminOfUserDeactivation::class,
        ],
        \App\Events\UserEmailVerified::class => [
            \App\Listeners\NotifyUserEmailVerified::class,
        ],
        \App\Events\CourseCreated::class => [
            \App\Listeners\NotifyAdminsCourseCreated::class,
        ],
        \App\Events\CoursePublished::class => [
            \App\Listeners\NotifyStudentCoursePublished::class,
        ],
        \App\Events\CourseUnenrolled::class => [
            \App\Listeners\NotifyInstructorStudentUnenrolled::class,
        ],
        \App\Events\LessonStarted::class => [
            \App\Listeners\InitializeAttendance::class,
            \App\Listeners\UpdateProgress::class,
        ],
        \App\Events\LessonCompleted::class => [
            \App\Listeners\UpdateProgress::class,
            \App\Listeners\UnlockNextLesson::class,
        ],
        \App\Events\QuizCreated::class => [
            \App\Listeners\NotifyInstructorQuizCreated::class,
        ],
        \App\Events\QuizStarted::class => [
            \App\Listeners\JoinRealtimeQuizRoom::class,
            \App\Listeners\MarkQuizAttendance::class,
        ],
        \App\Events\QuizCompleted::class => [
            \App\Listeners\CalculateFinalScore::class,
            \App\Listeners\UpdateRealtimeLeaderboard::class,
            \App\Listeners\NotifyStudentQuizCompleted::class,
            \App\Listeners\NotifyInstructorQuizCompleted::class,
            \App\Listeners\EnableCertification::class,
        ],
        \App\Events\QuizAnswerSubmitted::class => [
            \App\Listeners\UpdateRealtimeScore::class,
            \App\Listeners\RunAntiCheatChecks::class,
        ],
        \App\Events\QuizLeaderboardUpdated::class => [
            \App\Listeners\BroadcastLeaderboardUpdates::class,
        ],
        \App\Events\AssignmentCreated::class => [
            \App\Listeners\NotifyStudentsAssignmentCreated::class,
        ],
        \App\Events\AssignmentSubmitted::class => [
            \App\Listeners\NotifyInstructorAssignmentSubmitted::class,
        ],
        \App\Events\AssignmentGraded::class => [
            \App\Listeners\NotifyStudentsAssignmentGraded::class,
        ],
        \App\Events\AttendanceMarked::class => [
            \App\Listeners\PersistAttendanceRecord::class,
            \App\Listeners\MarkOnlineAttendance::class,
        ],
        \App\Events\AttendanceCorrected::class => [
            \App\Listeners\NotifyAdminsAttendanceCorrected::class,
        ],
        \App\Events\MaterialDownloaded::class => [
            \App\Listeners\IncrementDownloadCounter::class,
        ],
        \App\Events\PortifolioGenerated::class => [
            \App\Listeners\UpdatePortifolio::class,
            \App\Listeners\NotifyStudentsPortifolioGenerated::class,
            \App\Listeners\NotifyAdminsPortifolioGenerated::class,
        ],
        \App\Events\PortifolioApproved::class => [
            \App\Listeners\UpdatePortifolioDraft::class,
            \App\Listeners\NotifyStudentsPortifolioApproved::class,
            \App\Listeners\TriggerPortifolioApproval::class,
        ],
        \App\Events\ChatMessageSent::class => [
            \App\Listeners\LogActivityListener::class,
        ],
        \App\Events\ChatRoomCreated::class => [
            \App\Listeners\LogActivityListener::class,
        ],
        \App\Events\AdminActionPerformed::class => [
            \App\Listeners\LogActivityListener::class,
        ],
        \App\Events\SystemSettingChanged::class => [
            \App\Listeners\LogActivityListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     */
    public function boot(): void
    {
        //
    }

    /**
     * Determine if events and listeners should be automatically discovered.
     */
    public function shouldDiscoverEvents(): bool
    {
        return false;
    }
}