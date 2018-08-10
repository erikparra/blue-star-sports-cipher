<html lang="en"><head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="favicon.ico">

    <title>Blue Star Cipher</title>

    <!-- Bootstrap core CSS -->
    <link href="dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
  </head>

  <body>

    <div class="d-flex flex-column flex-md-row align-items-center p-3 px-md-4 mb-3 bg-white border-bottom shadow-sm">
      <h5 class="my-0 mr-md-auto font-weight-normal">Erik Parra</h5>
      <nav class="my-2 my-md-0 mr-md-3">
        <a class="p-2 text-dark" href="https://github.com/erikparra/blue-star-sports-cipher">Github Repo</a>
      </nav>
    </div>

    <div class="pricing-header px-3 py-3 pt-md-5 pb-md-4 mx-auto text-center">
      <h1 class="display-4">Cipher Challenge</h1>
      <p class="lead">During World War II, Alan Turing (who is considered the father of modern computing) used computational analysis, and created the first computer to decrypt German messages. Given a key, the computer could decrypt messages at an alarming pace.</p>
    </div>

    <div class="container">
      <div class="card-deck mb-3 text-center">
        <div class="card mb-4 shadow-sm">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">Encrypted.txt</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">7 <small class="text-muted"> sec</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>Given plain.txt</li>
              <li>Decrypt encrypted.txt</li>
            </ul>
            <a class="btn btn-lg btn-block btn-outline-primary" href="freqAnalysis.php?cipher=encrypted">Decrypt</a>
          </div>
        </div>
        <div class="card mb-4 shadow-sm">
          <div class="card-header">
            <h4 class="my-0 font-weight-normal">Encrypted_hard.txt</h4>
          </div>
          <div class="card-body">
            <h1 class="card-title pricing-card-title">7 <small class="text-muted"> sec</small></h1>
            <ul class="list-unstyled mt-3 mb-4">
              <li>Given plain.txt</li>
              <li>Decrypt encrypted_hard</li>
            </ul>
            <a class="btn btn-lg btn-block btn-primary" href="freqAnalysis.php?cipher=encrypted_hard">Decrypt</a>
          </div>
        </div>
      </div>

      <footer class="pt-4 my-md-5 pt-md-5 border-top">
        <div class="row">
          <div class="col-12 col-md">
            <img class="mb-2" src="assets/brand/bootstrap-solid.svg" alt="" width="24" height="24">
            <small class="d-block mb-3 text-muted">© 2017-2018</small>
          </div>
        </div>
      </footer>
    </div>


    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script>window.jQuery || document.write('<script src="assets/js/vendor/jquery-slim.min.js"><\/script>')</script>
    <script src="assets/js/vendor/popper.min.js"></script>
    <script src="dist/js/bootstrap.min.js"></script>
    <script src="assets/js/vendor/holder.min.js"></script>
    <script>
      Holder.addTheme('thumb', {
        bg: '#55595c',
        fg: '#eceeef',
        text: 'Thumbnail'
      });
    </script>


</body></html>
