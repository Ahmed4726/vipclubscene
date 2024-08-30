<!DOCTYPE html>
<html>
<head>
    <title>Schedule Post</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
      <div class="card">
    <div class="card-header">
        Schedule Post
    </div>
    <div class="card-body">
        <form action="" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="file">Upload File</label>
                <input type="file" class="form-control" id="file" name="file" required>
            </div>
            <div class="form-group">
                <label for="caption">Write a Caption</label>
                <textarea class="form-control" id="caption" name="caption" rows="4" required></textarea>
            </div>
            <div class="form-group">
                <label for="schedule_time">Schedule Time</label>
                <input type="datetime-local" class="form-control" id="schedule_time" name="schedule_time" required>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary">Post</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('file').value=''; document.getElementById('caption').value=''; document.getElementById('schedule_time').value='';">Discard</button>
            </div>
        </form>
    </div>
</div>

    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
