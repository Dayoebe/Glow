<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Vettas Reservation</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937;">
    <p>Hello,</p>

    <p>A new Vettas reservation request has been submitted from Glow FM.</p>

    <p><strong>Reservation Code:</strong> {{ $reservation->reservation_code }}</p>
    <p><strong>Guest Name:</strong> {{ $reservation->full_name }}</p>
    <p><strong>Email:</strong> {{ $reservation->email }}</p>
    <p><strong>Phone:</strong> {{ $reservation->phone }}</p>
    <p><strong>Check-In Date:</strong> {{ $reservation->check_in_date?->format('M d, Y') }}</p>
    <p><strong>Check-Out Date:</strong> {{ $reservation->check_out_date?->format('M d, Y') }}</p>
    <p><strong>Number of Guests:</strong> {{ $reservation->guest_count }}</p>
    <p><strong>Number of Nights:</strong> {{ $reservation->nights ?? 'N/A' }}</p>
    <p><strong>Status:</strong> {{ \Illuminate\Support\Str::of($reservation->status)->replace('-', ' ')->title() }}</p>
    <p><strong>Submitted At:</strong> {{ $reservation->created_at?->format('M d, Y g:i A') }}</p>

    <p><strong>Special Requests:</strong></p>
    <p>{!! nl2br(e($reservation->special_requests ?: 'None provided.')) !!}</p>

    <p>Please log in to the Glow FM dashboard to review and follow up with the guest.</p>

    <p>From Glow FM</p>
</body>
</html>
