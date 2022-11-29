window.addEventListener('DOMContentLoaded', () => {
  /* SWIPE > */
  const suggestionElt = document.querySelector('.suggestion')
  let xDown = null
  let yDown = null

  const getTouches = (evt) => {
    return evt.touches ||
      evt.originalEvent.touches
  }

  const handleTouchStart = (evt) => {
    const firstTouch = getTouches(evt)[0]
    xDown = firstTouch.clientX
    yDown = firstTouch.clientY
  }

  const handleTouchMove = (evt) => {
    if (!xDown || !yDown) {
      return
    }

    let xUp = evt.touches[0].clientX
    let yUp = evt.touches[0].clientY

    let xDiff = xDown - xUp
    let yDiff = yDown - yUp

    if (Math.abs(xDiff) > Math.abs(yDiff)) {
      if (xDiff > 0) {
        suggestionElt.animate(
          [
            { 'transform-origin': 'bottom left' },
            { 'transform': 'rotate(-5deg) translateX(-10%)' }
          ],
          {
            duration: 250,
            iterations: 1
          })
        Promise.all(suggestionElt.getAnimations().map((animation) => animation.finished)).then(
          () => window.location.href = '/'
        )
      } else {
        suggestionElt.animate(
          [
            { 'transform-origin': 'top right' },
            { 'transform': 'rotate(5deg) translateX(10%)' }
          ],
          {
            duration: 250,
            iterations: 1
          })
        Promise.all(suggestionElt.getAnimations().map((animation) => animation.finished)).then(
          () => {
            window.location.href = '/album/modifier/' + suggestionElt.dataset.albumid
          }
        )
      }
    } else {
      if (yDiff > 0) {
        suggestionElt.animate(
          [
            { 'transform': 'translateY(-10%)' }
          ],
          {
            duration: 250,
            iterations: 1
          })
        Promise.all(suggestionElt.getAnimations().map((animation) => animation.finished)).then(
          () => window.location.href = '/ecouter/' + suggestionElt.dataset.albumid
        )
      } else {
        console.log('swiping down does nothing')
      }
    }

    xDown = null
    yDown = null
  }
  document.addEventListener('touchstart', handleTouchStart, false)
  document.addEventListener('touchmove', handleTouchMove, false)
  /* < SWIPE */

  /* CLICK > */
  const suggestBtns = document.querySelectorAll('.suggestion .action')
  suggestBtns.forEach(btn => {
    btn.addEventListener('click', event => {
      event.preventDefault()
      if (event.target.parentNode.parentNode.classList.contains('top') || event.target.parentNode.classList.contains('top') || event.target.classList.contains('top')) {
        suggestionElt.animate(
          [
            { 'transform': 'translateY(-10%)' }
          ],
          {
            duration: 250,
            iterations: 1
          })
        Promise.all(suggestionElt.getAnimations().map((animation) => animation.finished)).then(
          () => window.location.href = '/ecouter/' + suggestionElt.dataset.albumid
        )
      } else if (event.target.parentNode.parentNode.classList.contains('left') || event.target.parentNode.classList.contains('left') || event.target.classList.contains('left')) {
        suggestionElt.animate(
          [
            { 'transform-origin': 'bottom left' },
            { 'transform': 'rotate(-5deg) translateX(-10%)' }
          ],
          {
            duration: 250,
            iterations: 1
          })
        Promise.all(suggestionElt.getAnimations().map((animation) => animation.finished)).then(
          () => window.location.href = '/'
        )
      } else if (event.target.parentNode.parentNode.classList.contains('right') || event.target.parentNode.classList.contains('right') || event.target.classList.contains('right')) {
        suggestionElt.animate(
          [
            { 'transform-origin': 'top right' },
            { 'transform': 'rotate(5deg) translateX(10%)' }
          ],
          {
            duration: 250,
            iterations: 1
          })
        Promise.all(suggestionElt.getAnimations().map((animation) => animation.finished)).then(
          () => {
            window.location.href = '/album/modifier/' + suggestionElt.dataset.albumid
          }
        )
      }
    })
  })
  /* < CLICK */

  /* LIKE > */
  const likeBtn = document.getElementById('like')
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
  /* < LIKE */
})
