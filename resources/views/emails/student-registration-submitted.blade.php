New paid student registration on {{ config('cyra.name') }}

Reference: {{ $registration->reference }}
Payment status: {{ ucfirst($registration->payment_status) }}
Submitted: {{ $registration->created_at?->format('M j, Y g:i A') }}
Paid at: {{ $registration->paid_at?->format('M j, Y g:i A') ?? 'N/A' }}

Name: {{ $registration->name }}
Email: {{ $registration->email }}
Phone: {{ $registration->phone }}
Date of birth: {{ $registration->date_of_birth?->format('F j, Y') ?? 'N/A' }}
Gender: {{ $registration->gender }}
Location: {{ collect([$registration->city, $registration->state])->filter()->implode(', ') }}
Education: {{ $levelLabel }}
Field of study: {{ $registration->field_of_study ?: $registration->course_of_study }}
Skill level: {{ $registration->skill_level }}
Previous tech experience: {{ $registration->has_tech_experience ? 'Yes' : 'No' }}
Program: {{ $programLabel }}
Amount paid: {{ $amountFormatted }}
Paystack reference: {{ $registration->paystack_reference }}

—
You can reply directly to this email to reach {{ $registration->name }}.
