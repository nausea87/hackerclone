# Hackerclone

Big x-mas project!

#

1. Clone this repo, start a localhost in /public
2. Let your eyes bleed

## Features

User should be able to:

1. Create an account.
2. Login & logout.
3. Edit account email, password and biography.
4. Change avatar image.
5. Create new posts with title, link and description.
6. Edit posts.
7. Delete posts.
8. View most upvoted posts.
9. View new posts.
10. Upvote posts.
11. Remove upvote from posts.
12. Comment on a post.
13. Edit own comments.
14. Delete own comments.

## Requirements

1. The application should be written in HTML, CSS, SQL, PHP and JS (optional).
2. The application should be built using a SQLite database with at least four different tables.
3. Pushed to public repository on GitHub.
4. Responsive and mobile-first.
5. Implemented secure hashed passwords when signing up.
6. The project should contain the files and directories in the resources folder in the root of your repository.
7. The project should implement an accessible graphical user interface.
8. Declare strict types in PHP-only files.
9. No errors, warnings or notices.
10. Tested on 2+ classmates. Add their names to the README.md
11. The project must recieve a code review by another classmate. Atleast 10 comments with a pull request.

## Other

1. Atleast 30 commits and not just one big.
2. Repo should contain the database.
3. README with instructions.
4. LICENSE.
5. .editorconfig with my preferred settings.

## Comments from Joacim Johansson:

- index.php/ @ row 4: Nice use of function to check for logged users, I keep forgetting that myself.

- index.php/ @ row 8: Good to remember to unset SESSION variabels ones they've served their use. I keep forgetting that as well.

- index.php/ @ rows 20 - 25: A lot of divs here. Div may be good to wrap text, hower, I would personally recommend using a "figure" and "figcaption" as follows figure>img>figcaption>h2>/figcap>/figure>.

- views/navigation.php/: Clever use of require. I had a bunch of if statements in my header.php. Gonna see about implmenting this myself in the future!

- app/function.php/: curious why this is under app and not views (mostly cause I wonder if I placed my wrong then).

- app/autload.php/ @ row 21: Be mind ful of keeping a databas connection open when you don't need it. Makes a good breach for hackers.

- app/users/delete-account.php/ $ ror 26: Did you manage to also remove the users postst and comment? Seems a bit incompletee to me, or did you get a CASCADE on DELETE function work on the DB?

Erik: delete-account.php was something I couldn't finish in time. Though about deleting it pre-hand in but I want to keep working on this.

- app/posts/store.php/ @ row 12-13: Might consider making a custom function for this. Don't forget to validate urls using filter_var too :)

- assets/styles/comments.css/ @ row 35-38: Don't forget to delete commented code before the hand in.

- assets/scripts/main.js/ @ row 13-43: Intresting method to use JS to fetch and build the data. I would still recomend building in HTML with PHP variable load in.

Good work in general, might wanna look over the folder structures as a lot of files are named similiar and can be found in odd places (to the unsused)! :D

// Joey Jay

### Testers:

Joacim Johansson & Joakim Sjögren
