New contact form submission on {{ config('cyra.name') }}

Reference: {{ $inquiry->reference }}
Inquiry type: {{ $inquiryTypeLabel }}
Submitted: {{ $inquiry->created_at?->format('M j, Y g:i A') }}

Name: {{ $inquiry->name }}
Email: {{ $inquiry->email }}
Company: {{ $inquiry->company ?: '—' }}
Phone: {{ $inquiry->phone ?: '—' }}

Message:
{{ $inquiry->message }}

—
You can reply directly to this email to reach {{ $inquiry->name }}.
