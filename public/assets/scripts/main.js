// Breakout in several JS


"use_strict";
// To remove comments after a second
const messages = document.querySelectorAll(".messages");
messages.forEach(message => {
    setTimeout(() => {
        message.parentElement.removeChild(message);
    }, 1000);
    console.log('this works!');
});

//Generate comment & reply form
const commentForms = document.querySelectorAll(".comment-form");
const showCommentsForms = document.querySelectorAll(".show-comments-form");
const createCommentTemplate = (
    userId,
    avatar,
    username,
    comment,
    commentId,
    
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
                <button class="reply-btn" type="submit">reply</button>
            </form>
            <ul class="reply-list"></ul>
            <form class="reply-form" action="" method="post">
                <div class="avatar-container">
                    <img class="avatar" src="/uploads/avatars/${avatar}" alt="avatar">
                </div>
                <input type="hidden" name="id" value="${commentId}">
                <textarea name="reply" cols="40" rows="1" placeholder="reply..." required></textarea>
                <button type="submit">Send</button>
            </form>`;
};

//Post form 
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
               if (json.valid === false) {
                    window.alert(json.errors);
                } else {
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
                    
                    let showRepliesForm = comment.querySelector(
                        ".show-replies-form"
                    );
                    activateReplyButton(showRepliesForm);
                  
                    let replyForm = comment.querySelector(".reply-form");
                    activateReplyForm(replyForm);
                  
                    const commentInput = commentForm.querySelector("textarea");
                    commentInput.value = "";
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
    });
});

//Get comments on post (Clean this up)
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
               
                if (json.valid === false) {
                    window.alert(json.errors);
                } else {
                    const showCommentsButton = event.target.querySelector(
                        ".show-comments-button"
                    );
                    const commentList = event.target.parentElement.querySelector(
                        ".comment-list"
                    );
                    
                    if (
                        showCommentsButton.classList.contains("active") ===
                        false
                    ) {
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
                          
                            const replyButton = comment.querySelector(
                                ".show-replies-form .reply-btn"
                            );
                            replyButton.textContent = response.buttonText;
                            
                            let showRepliesForm = comment.querySelector(
                                ".show-replies-form"
                            );
                            activateReplyButton(showRepliesForm);
                            
                            let replyForm = comment.querySelector(
                                ".reply-form"
                            );
                            activateReplyForm(replyForm);
                        });
                      
                        showCommentsButton.classList.add("active");
                        showCommentsButton.textContent = "Show Less";
                    } else {
                      
                        const comments = commentList.querySelectorAll(
                            ".comment"
                        );
                        for (let i = 0; i < json.comments.length - 2; i++) {
                            const comment = comments[i];
                            comment.parentElement.removeChild(comment);
                        }
                        
                        showCommentsButton.classList.remove("active");
                        showCommentsButton.textContent = `comments`;
                    }
                }
            })
            .catch(error => {
                console.error("Error:", error);
            });
    });
});

//DELETE OWN POSTS

const deletePostForm = document.querySelector(".delete-post-form");

if (deletePostForm !== null) {
    deletePostForm.addEventListener("submit", event => {
        if (!window.confirm("Sure about this?")) {
            event.preventDefault();
        }
        console.log('Post deleted');
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

// Like formatting
const formatLikes = numOfLikes => {
    const int = Number(numOfLikes);
    if (int === 0) {
        return "";
    }
    else {
        return numOfLikes + " Likes";
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
                const likeNum = event.target.parentElement.querySelector(
                    "p"
                );
                likeBtn.textContent = json.buttonText;
                likeNum.textContent = formatLikes(json.numberOfLikes);
            })
            .catch(error => {
                console.error("Error:", error);
            });
    });
});


// REPLIES
const createReplyTemplate = (userId, avatar, username, reply) => {
    return `<a href="/profile.php?id=${userId}">
      <div class="avatar-container">
       <img class="avatar" src="/uploads/avatars/${avatar}" alt="avatar">
      </div>
    </a>
    <p><a href="/profile.php?id=${userId}"><span>${username}</span>
    </a>${reply}</p>`;
};


const createReplyButtonText = numberOfReplies => {
    if (numberOfReplies === 0) {
        return "reply";
    } 
    else {
        return `show replies`;
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
                        ".reply-btn"
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