<?php include "header.php"; ?>
<div class="container mt-5">
    <h1 class="mb-4">Profile</h1>
    <ul class="nav nav-tabs mb-4" id="myTab" role="tablist">
        <li class="nav-item" role="presentation">
            <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="true">Profile</a>
        </li>
        <li class="nav-item" role="presentation">
            <a class="nav-link" id="movies-tab" href="/profile/list_movies" aria-controls="movies" aria-selected="false">My Movies</a>
        </li>
    </ul>
    <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="profile" role="tabpanel" aria-labelledby="profile-tab">
            <form method="post" action="/user/update/<?php echo $currentUser->getId(); ?>">
                <div class="form-group">
                    <label for="name">Name:</label>
                    <input type="text" class="form-control" id="name" name="name" value="<?php echo $currentUser->getName(); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo $currentUser->getEmail(); ?>"  required>
                </div>
                <a href="movie" class="btn btn-primary"> Add new movie </a>
                <button type="submit" class="btn btn-primary">Save</button>
                <a href="logout" class="btn btn-primary">Logout</a>
            </form>
        </div>
        <div class="tab-pane fade" id="list_movies" role="tabpanel" aria-labelledby="movies-tab">
            <?php require_once 'list_movies.php'; ?>
        </div>
    </div>
</div>
<?php include "footer.php"; ?>
