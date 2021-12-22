<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <title>AHA eLearning Spider</title>
  </head>

  <body class="lead">
    <header class="py-5 mb-4 border-bottom bg-light">
      <div class="container">
        <h1 class="display-3 fw-bold">AHA eLearning Spider &#x1F577;</h1>
        <p>This tool tells you which AHA eLearning codes are used or unused.</p>
        <p><em>Brought to you by William Entriken.</em></p>
      </div>
    </header>

    
    <form method="post" action="start-report.php">

    <div class="container my-5">      
      <div class="row">
        <div class="col">
          <h2>Enrollment URLs</h2>
          <textarea name="targets" rows=8 class="form-control"></textarea>
          <p>These look like: https://elearning.heart.org/course_enrolment?course=XXX&code=XXXXXXXXXXXX&rand=XXXXXXXXXXXX</p>
        </div>
      </div>

      <p><button class="btn btn-lg btn-primary">Run report &raquo;</button></p>
    </div> <!-- /container -->
  </body>
</html>
