<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Certificate</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      font-family: "DejaVu Sans", "Times New Roman", serif;
    }

    .certificate {
      position: relative;
      width: 1000px;
      height: 700px;
      margin: 0 auto;
      background: url("{{ public_path('images/signed-certificate-template.png') }}") no-repeat center;
      background-size: cover;
    }

    .content {
      position: absolute;
      top: 220px; /* adjust according to background */
      left: 50%;
      transform: translateX(-50%);
      width: 80%;
      text-align: center;
    }

    .title {
      font-size: 28px;
      font-weight: bold;
      margin-bottom: 5px;
    }

    .subtitle {
      font-size: 18px;
      margin-bottom: 5px;
    }

    .name {
      font-size: 22px;
      font-weight: bold;
      margin: 5px 0;
      text-decoration: underline;
    }

    .course-title {
      font-size: 20px;
      font-weight: bold;
      margin: 10px 0;
    }

    .details {
      font-size: 18px;
      margin-top: 10px;
      line-height: 1.5;
    }
  </style>
</head>
<body>
  <div class="certificate">
    <div class="content">
      <div class="title">Certificate of Attendance</div>
      <div class="subtitle">{{ config('app.name') }} is pleased to award</div>

      <div class="name">{{ $certificate->recipient_name }}</div>

      <div class="subtitle">A certificate of attendance for the course on</div>
      <div class="course-title">{{ $certificate->course->title }}</div>

      <div class="details">
        Held on <b>{{ $certificate->course->start_date->format('d M, Y') }} - {{ $certificate->course->end_date->format('d M, Y') }}</b><br>
        in <b>{{ $certificate->course->country->name ?? '' }}</b> at <b>{{ $certificate->course->venue ?? '' }}</b>
      </div>
    </div>
  </div>
</body>
</html>
