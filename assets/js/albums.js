window.addEventListener('DOMContentLoaded', () => {
  /* LIKE > */
  const likeBtns = document.querySelectorAll('.like')
  likeBtns.forEach(likeBtn => {
    likeBtn.addEventListener('click', e => {
      e.preventDefault()
      fetch('/like/' + likeBtn.dataset.albumid)
        .then(response => response.json())
        .then(response => {
          console.log(response)
          if (response === 'like') {
            likeBtn.querySelector('i').classList.remove('fa-regular')
            likeBtn.querySelector('i').classList.add('fa-solid')
          } else {
            likeBtn.querySelector('i').classList.remove('fa-solid')
            likeBtn.querySelector('i').classList.add('fa-regular')
          }
        })
    })
  })
  /* < LIKE */
})
