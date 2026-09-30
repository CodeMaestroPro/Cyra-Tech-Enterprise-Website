New IT student registration on {{ config('cyra.name') }}

Reference: {{ $registration->reference }}
Submitted: {{ $registration->created_at?->format('M j, Y g:i A') }}

Name: {{ $registration->name }}
Email: {{ $registration->email }}
Phone: {{ $registration->phone }}
Institution: {{ $registration->institution }}
Course of study: {{ $registration->course_of_study }}
Academic level: {{ $levelLabel }}
Interest area: {{ $interestLabel }}
Availability: {{ $availabilityLabel }}

Motivation:
{{ $registration->motivation }}

—
You can reply directly to this email to reach {{ $registration->name }}.
