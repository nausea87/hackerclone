"use_strict";

const messages = document.querySelectorAll(".messages");
messages.forEach(message => {
    setTimeout(() => {
        message.parentElement.removeChild(message);
    }, 1000);
});

//COMMENTS
const commentForms = document.querySelectorAll(".comment-form");
const showCommentsForms = document.querySelectorAll(".show-comments-form");
const createCommentTemplate = (
    userId,
    avatar,
    username,
    comment,
    commentId,
    loggedInUserAvatar
) => {
    return `<li class="comment-container">
                <a href="/profile.php?id=${userId}">
                    <div class="avatar-container">
                        <img class="avatar" src="/uploads/avatars/${avatar}" alt="avatar">
                    </div>
                </a>
                <p><a href="/profile.php?id=${userId}"><span>${username}</span></a>${comment}</p>
            </li>
            <form class="show-replies-form" action="" method="post">
                <input type="hidden" name="id" value="${commentId}">
                <button class="reply-button" type="submit">reply</button>
            </form>
            <ul class="reply-list"></ul>
            <form class="reply-form" action="" method="post">
                <div class="avatar-container">
                    <img class="avatar" src="/uploads/avatars/${loggedInUserAvatar}" alt="avatar">
                </div>
                <input type="hidden" name="id" value="${commentId}">
                <textarea name="reply" cols="45" rows="1" maxlength="140" placeholder="reply..." required></textarea>
                <button type="submit">Send</button>
            </form>`;
};

//post the form data and append valid comment to the comment-list
commentForms.forEach(commentForm => {
    commentForm.addEventListener("submit", event => {
        event.preventDefault();
        const formData = new FormData(commentForm);
        fetch("http://localhost:8000/app/posts/createcomment.php", {
            method: "POST",
            body: formData
        })
            .then(response => {
                return response.json();
            })
            .then(json => {
                //display problems with invalid $_POST data
                if (json.valid === false) {
                    window.alert(json.errors);
                } else {
                    //append the comment to the comment-list
                    const commentList = event.target.parentElement.querySelector(
                        ".comment-list"
                    );
                    const comment = document.createElement("li");
                    comment.classList.add("comment");
                    comment.innerHTML = createCommentTemplate(
                        json.comment.user_id,
                        json.user.avatar,
                        json.user.username,
                        json.comment.comment,
                        json.comment.id,
                        json.loggedInUser.avatar
                    );
                    commentList.appendChild(comment);
                    //activate the reply-button
                    let showRepliesForm = comment.querySelector(
                        ".show-replies-form"
                    );
                    activateReplyButton(showRepliesForm);
                    //activate the reply-form
                    let replyForm = comment.querySelector(".reply-form");
                    activateReplyForm(replyForm);
                    //empty the input field after comment is appended
                    const commentInput = commentForm.querySelector("textarea");
                    commentInput.value = "";
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
    });
});

//get all comments on a post toggle to show all or less
showCommentsForms.forEach(showCommentsForm => {
    showCommentsForm.addEventListener("submit", event => {
        event.preventDefault();
        const formData = new FormData(showCommentsForm);
        fetch("http://localhost:8000/app/posts/showcomments.php", {
            method: "POST",
            body: formData
        })
            .then(response => {
                return response.json();
            })
            .then(json => {
                //display problems with the POST data
                if (json.valid === false) {
                    window.alert(json.errors);
                } else {
                    const showCommentsButton = event.target.querySelector(
                        ".show-comments-button"
                    );
                    const commentList = event.target.parentElement.querySelector(
                        ".comment-list"
                    );
                    //show all comments if button hasn't been pressed already
                    if (
                        showCommentsButton.classList.contains("active") ===
                        false
                    ) {
                        //add all comments to the comment-list
                        commentList.innerHTML = "";
                        json.comments.forEach(response => {
                            const comment = document.createElement("article");
                            comment.classList.add("comment");
                            comment.innerHTML = createCommentTemplate(
                                response.user_id,
                                response.avatar,
                                response.username,
                                response.comment,
                                response.id,
                                json.loggedInUser.avatar
                            );
                            commentList.appendChild(comment);
                            //add reply-button text to comment
                            const replyButton = comment.querySelector(
                                ".show-replies-form .reply-button"
                            );
                            replyButton.textContent = response.buttonText;
                            //activate the reply-button on comment
                            let showRepliesForm = comment.querySelector(
                                ".show-replies-form"
                            );
                            activateReplyButton(showRepliesForm);
                            //activate the reply-form on comment
                            let replyForm = comment.querySelector(
                                ".reply-form"
                            );
                            activateReplyForm(replyForm);
                        });
                        //add active class and change button text
                        showCommentsButton.classList.add("active");
                        showCommentsButton.textContent = "Show Less";
                    } else {
                        //remove all comments except the last 2
                        const comments = commentList.querySelectorAll(
                            ".comment"
                        );
                        for (let i = 0; i < json.comments.length - 2; i++) {
                            const comment = comments[i];
                            comment.parentElement.removeChild(comment);
                        }
                        //remove active class and change button text
                        showCommentsButton.classList.remove("active");
                        showCommentsButton.textContent = `show all ${json.comments.length} comments`;
                    }
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
    });
});

//EDIT POSTS

const deletePostForm = document.querySelector(".delete-post-form");

if (deletePostForm !== null) {
    deletePostForm.addEventListener("submit", event => {
        if (!window.confirm("Are you sure you want to delete post?")) {
            event.preventDefault();
        }
    });
}

// PROFILE EDITS

"use_strict";

const showFormButtons = document.querySelectorAll(".show-form-button");

showFormButtons.forEach(btn => {
    btn.addEventListener("click", event => {
        const form = event.target.parentElement.parentElement.querySelector(
            "form"
        );
        form.classList.toggle("visible");
    });
});

// LIKE JS
"use strict";
const formatLikes = numberOfLikes => {
    const int = Number(numberOfLikes);
    if (int === 0) {
        return "";
    }
    if (int === 1) {
        return "1 Like";
    }
    if (int > 1) {
        return numberOfLikes + " Likes";
    }
};

const likeForms = document.querySelectorAll(".like-form");

likeForms.forEach(likeForm => {
    likeForm.addEventListener("submit", event => {
        event.preventDefault();
        const formData = new FormData(likeForm);
        fetch("http://localhost:8000/app/posts/likes.php", {
            method: "POST",
            body: formData
        })
            .then(response => {
                return response.json();
            })
            .then(json => {
                const likeBtn = event.target.querySelector("button");
                const likeNumber = event.target.parentElement.querySelector(
                    "p"
                );
                likeBtn.textContent = json.buttonText;
                likeNumber.textContent = formatLikes(json.numberOfLikes);
            })
            .catch(error => {
                console.error("Error:", error);
            });
    });
});


// REPLIES
"use_strict";

const createReplyTemplate = (userId, avatar, username, reply) => {
    return `<a href="/profile.php?id=${userId}">
      <div class="avatar-container">
       <img class="avatar" src="/uploads/avatars/${avatar}" alt="avatar">
      </div>
    </a>
    <p><a href="/profile.php?id=${userId}"><span>${username}</span></a>${reply}</p>`;
};

const createReplyButtonText = numberOfReplies => {
    if (numberOfReplies === 0) {
        return "reply";
    } else if (numberOfReplies === 1) {
        return "show 1 reply";
    } else {
        return `show ${numberOfReplies} replies`;
    }
};

const activateReplyButton = showRepliesForm => {
    showRepliesForm.addEventListener("submit", event => {
        event.preventDefault();
        const replyForm = event.target.parentElement.querySelector(
            ".reply-form"
        );
        replyForm.classList.toggle("visible");
        const formData = new FormData(showRepliesForm);
        fetch("http://localhost:8000/app/posts/showreplies.php", {
            method: "POST",
            body: formData
        })
            .then(response => {
                return response.json();
            })
            .then(json => {
              
                if (json.valid === false) {
                    window.alert(json.errors);
                } else {
                    const replyButton = event.target.querySelector(
                        ".reply-button"
                    );
                    const replyList = event.target.parentElement.querySelector(
                        ".reply-list"
                    );
                    if (replyButton.classList.contains("active") === false) {
                       
                        json.replies.forEach(response => {
                            const replyTemplate = createReplyTemplate(
                                response.user_id,
                                response.avatar,
                                response.username,
                                response.reply
                            );
                            const reply = document.createElement("li");
                            reply.classList.add("reply");
                            reply.innerHTML = replyTemplate;
                            replyList.appendChild(reply);
                        });
                      
                        replyButton.classList.add("active");
                        replyButton.textContent = "hide replies";
                    } else {
                        
                        replyList.innerHTML = "";
                        replyButton.classList.remove("active");
                        replyButton.textContent = createReplyButtonText(
                            json.replies.length
                        );
                    }
                }
            });
    });
};

const activateReplyForm = replyForm => {
    replyForm.addEventListener("submit", event => {
        event.preventDefault();
        const formData = new FormData(replyForm);
        fetch("http://localhost:8000/app/posts/createreply.php", {
            method: "POST",
            body: formData
        })
            .then(reply => {
                return reply.json();
            })
            .then(json => {
               
                if (json.valid === false) {
                    window.alert(json.errors);
                } else {
                    
                    const replyList = event.target.parentElement.querySelector(
                        ".reply-list"
                    );
                    const response = json.reply;
                    const replyTemplate = createReplyTemplate(
                        response.user_id,
                        response.avatar,
                        response.username,
                        response.reply
                    );
                    const reply = document.createElement("li");
                    reply.classList.add("reply");
                    reply.innerHTML = replyTemplate;
                    replyList.appendChild(reply);
                    replyForm.querySelector("textarea").value = "";
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
    });
};

"use_strict";

const showRepliesForms = document.querySelectorAll(".show-replies-form");
const replyForms = document.querySelectorAll(".reply-form");

//Activate the reply buttons on initially available comments
showRepliesForms.forEach(showRepliesForm => {
    activateReplyButton(showRepliesForm);
});

//Activate the reply forms on initially available comments
replyForms.forEach(replyForm => {
    activateReplyForm(replyForm);
});