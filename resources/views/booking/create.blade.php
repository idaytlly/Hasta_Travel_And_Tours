<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Book {{ $car->name }} | Hasta Travel & Tours</title>
  <link href="https://cdn.jsdelivr.net/npm/bootswatch@5.3.3/dist/lux/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <style>
    body { font-family: Arial, sans-serif; background: #f5f5f5; }
    .card { max-width: 600px; margin: 50px auto; }
  </style>
</head>
<body>

@include('partials.navbar')

<div class="card shadow-sm p-4">
  <h3 class="mb-4">Booking: {{ $car->name }}</h3>

  <form method="POST" action="{{ route('booking.store', $car) }}" enctype="multipart/form-data">
    @csrf

    <div class="mb-3">
      <label class="form-label">Start Date</label>
      <input type="date" name="start_date" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">End Date</label>
      <input type="date" name="end_date" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Upload Document (ID/License)</label>
      <input type="file" name="document" class="form-control" accept=".pdf,.jpg,.png" required>
    </div>

    <button type="submit" class="btn btn-success w-100">Submit Booking</button>
  </form>
</div>

<footer class="text-center text-muted py-4">
  &copy; {{ date('Y') }} Hasta Travel & Tours
</footer>

</body>
</html>
