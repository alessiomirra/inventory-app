<main class="form-signin w-100 mt-5 m-auto">
  <form method="POST" action="/login">
    <p><i class="bi bi-door-open" style="font-size: 3rem;"></i></p>
    <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

    <?php

         if(!empty($_SESSION['message'])) :?>
         <div class="alert alert-danger">
             <?php
             echo htmlentities($_SESSION['message']);
             $_SESSION['message'] = '';
             ?>
         </div>
        <?php
        endif;

    ?>

    <input type="hidden" name="_csrf" value="<?= $token ?>">

    <div class="form-floating">
      <input type="email" name="email" class="form-control" id="floatingInput" placeholder="name@example.com">
      <label for="floatingInput">Email address</label>
    </div>
    <div class="form-floating">
      <input type="password" name="password" class="form-control" id="floatingPassword" placeholder="Password">
      <label for="floatingPassword">Password</label>
    </div>

    <button class="btn btn-primary w-100 py-2" type="submit">Sign in</button>

  </form>
</main>