<!---EDIT own comments --->
<?php if (isYourComment($pdo, $_SESSION['user']['id'], $comment['id'])) : ?>
    <button></button>
<?php endif; ?>