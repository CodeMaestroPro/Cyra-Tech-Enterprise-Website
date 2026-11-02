New free training registration on {{ config('cyra.name') }}

Reference: {{ $registration->reference }}
Submitted: {{ $registration->created_at?->format('M j, Y g:i A') }}

Name: {{ $registration->name }}
Email: {{ $registration->email }}
Phone: {{ $registration->phone }}
Date of birth: {{ $registration->date_of_birth?->format('F j, Y') ?? 'N/A' }}
Gender: {{ $registration->gender }}
Location: {{ collect([$registration->city, $registration->state])->filter()->implode(', ') }}
Skill level: {{ $registration->skill_level }}
Previous tech experience: {{ $registration->has_tech_experience ? 'Yes' : 'No' }}
Course: {{ $programLabel }}

—
You can reply directly to this email to reach {{ $registration->name }}.
