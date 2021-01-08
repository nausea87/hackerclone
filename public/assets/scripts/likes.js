// "use strict";

// const formatLikes = numberOfLikes => {
//     const int = Number(numberOfLikes);
//     if (int === 0) {
//         return "";
//     }
//     if (int === 1) {
//         return "1 Like";
//     }
//     if (int > 1) {
//         return numberOfLikes + " Likes";
//     }
// };

// const likeForms = document.querySelectorAll(".like-form");

// likeForms.forEach(likeForm => {
//     likeForm.addEventListener("submit", event => {
//         event.preventDefault();
//         const formData = new FormData(likeForm);
//         fetch("http://localhost:8000/app/posts/likes.php", {
//             method: "POST",
//             body: formData
//         })
//             .then(response => {
//                 return response.json();
//             })
//             .then(json => {
//                 const likeBtn = event.target.querySelector("button");
//                 const likeNumber = event.target.parentElement.querySelector(
//                     "p"
//                 );
//                 likeBtn.textContent = json.buttonText;
//                 likeNumber.textContent = formatLikes(json.numberOfLikes);
//             })
//             .catch(error => {
//                 console.error("Error:", error);
//             });
//     });
// });
