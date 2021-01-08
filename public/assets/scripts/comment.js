// const commentForms = document.querySelectorAll(".comment-form");
// const showCommentsForms = document.querySelectorAll(".show-comments-form");

// const createCommentTemplate = (
//     userId,
//     avatar,
//     username,
//     comment,
//     commentId,
//     loggedInUserAvatar
// ) => {
//     return `<li class="comment-container">
//                 <a href="/profile.php?id=${userId}">
//                     <div class="avatar-container">
//                         <img class="avatar" src="/uploads/avatars/${avatar}" alt="avatar">
//                     </div>
//                 </a>
//                 <p><a href="/profile.php?id=${userId}"><span>${username}</span></a>${comment}</p>
//             </li>
//             <form class="show-replies-form" action="" method="post">
//                 <input type="hidden" name="id" value="${commentId}">
//                 <button class="reply-button" type="submit">reply</button>
//             </form>
//             <ul class="reply-list"></ul>
//             <form class="reply-form" action="" method="post">
//                 <div class="avatar-container">
//                     <img class="avatar" src="/uploads/avatars/${loggedInUserAvatar}" alt="avatar">
//                 </div>
//                 <input type="hidden" name="id" value="${commentId}">
//                 <textarea name="reply" cols="45" rows="1" maxlength="140" placeholder="reply..." required></textarea>
//                 <button type="submit">Send</button>
//             </form>`;
// };

// //post the form data and append valid comment to the comment-list
// commentForms.forEach(commentForm => {
//     commentForm.addEventListener("submit", event => {
//         event.preventDefault();
//         const formData = new FormData(commentForm);
//         fetch("http://localhost:8000/app/posts/createcomment.php", {
//             method: "POST",
//             body: formData
//         })
//             .then(response => {
//                 return response.json();
//             })
//             .then(json => {
//                 //display problems with invalid $_POST data
//                 if (json.valid === false) {
//                     window.alert(json.errors);
//                 } else {
//                     //append the comment to the comment-list
//                     const commentList = event.target.parentElement.querySelector(
//                         ".comment-list"
//                     );
//                     const comment = document.createElement("li");
//                     comment.classList.add("comment");
//                     comment.innerHTML = createCommentTemplate(
//                         json.comment.user_id,
//                         json.user.avatar,
//                         json.user.username,
//                         json.comment.comment,
//                         json.comment.id,
//                         json.loggedInUser.avatar
//                     );
//                     commentList.appendChild(comment);
//                     //activate the reply-button
//                     let showRepliesForm = comment.querySelector(
//                         ".show-replies-form"
//                     );
//                     activateReplyButton(showRepliesForm);
//                     //activate the reply-form
//                     let replyForm = comment.querySelector(".reply-form");
//                     activateReplyForm(replyForm);
//                     //empty the input field after comment is appended
//                     const commentInput = commentForm.querySelector("textarea");
//                     commentInput.value = "";
//                 }
//             })
//             .catch(error => {
//                 console.error("Error:", error);
//             });
//     });
// });

// //get all comments on a post toggle to show all or less
// showCommentsForms.forEach(showCommentsForm => {
//     showCommentsForm.addEventListener("submit", event => {
//         event.preventDefault();
//         const formData = new FormData(showCommentsForm);
//         fetch("http://localhost:8000/app/posts/showcomments.php", {
//             method: "POST",
//             body: formData
//         })
//             .then(response => {
//                 return response.json();
//             })
//             .then(json => {
//                 //display problems with the POST data
//                 if (json.valid === false) {
//                     window.alert(json.errors);
//                 } else {
//                     const showCommentsButton = event.target.querySelector(
//                         ".show-comments-button"
//                     );
//                     const commentList = event.target.parentElement.querySelector(
//                         ".comment-list"
//                     );
//                     //show all comments if button hasn't been pressed already
//                     if (
//                         showCommentsButton.classList.contains("active") ===
//                         false
//                     ) {
//                         //add all comments to the comment-list
//                         commentList.innerHTML = "";
//                         json.comments.forEach(response => {
//                             const comment = document.createElement("article");
//                             comment.classList.add("comment");
//                             comment.innerHTML = createCommentTemplate(
//                                 response.user_id,
//                                 response.avatar,
//                                 response.username,
//                                 response.comment,
//                                 response.id,
//                                 json.loggedInUser.avatar
//                             );
//                             commentList.appendChild(comment);
//                             //add reply-button text to comment
//                             const replyButton = comment.querySelector(
//                                 ".show-replies-form .reply-button"
//                             );
//                             replyButton.textContent = response.buttonText;
//                             //activate the reply-button on comment
//                             let showRepliesForm = comment.querySelector(
//                                 ".show-replies-form"
//                             );
//                             activateReplyButton(showRepliesForm);
//                             //activate the reply-form on comment
//                             let replyForm = comment.querySelector(
//                                 ".reply-form"
//                             );
//                             activateReplyForm(replyForm);
//                         });
//                         //add active class and change button text
//                         showCommentsButton.classList.add("active");
//                         showCommentsButton.textContent = "Show Less";
//                     } else {
//                         //remove all comments except the last 2
//                         const comments = commentList.querySelectorAll(
//                             ".comment"
//                         );
//                         for (let i = 0; i < json.comments.length - 2; i++) {
//                             const comment = comments[i];
//                             comment.parentElement.removeChild(comment);
//                         }
//                         //remove active class and change button text
//                         showCommentsButton.classList.remove("active");
//                         showCommentsButton.textContent = `show all ${json.comments.length} comments`;
//                     }
//                 }
//             })
//             .catch(error => {
//                 console.error("Error:", error);
//             });
//     });
// });
