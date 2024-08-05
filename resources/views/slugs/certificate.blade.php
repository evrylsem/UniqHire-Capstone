<!-- TESTING PURPOSES xd -->
<!DOCTYPE html>
<html>

<head>
    <title>Certificate</title>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .certificate {
            text-align: center;
            padding: 50px;
            border: 1px solid #000;
            border-radius: 10px;
            margin: 20px;
        }

        .certificate h1 {
            font-size: 24px;
        }

        .certificate p {
            font-size: 18px;
        }

        .certificate .name {
            font-size: 22px;
            font-weight: bold;
        }

        .certificate .date {
            font-size: 18px;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <div class="certificate">
        <h1>Certificate of Completion</h1>
        <p>This is to certify that</p>
        <p class="name">{{ $user->userInfo->name }}</p>
        <p>has successfully completed the training program</p>
        <p class="name">{{ $trainingProgram->title }}</p>
        <p class="date">Date: {{ \Carbon\Carbon::now()->format('F j, Y') }}</p>
    </div>
</body>

</html>