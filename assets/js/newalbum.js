window.addEventListener('DOMContentLoaded', () => {
  /* SWIPE > */
  const resultsElt = document.querySelector('.results')
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

    if (Math.abs(yDiff) > Math.abs(xDiff)) {
      if (yDiff > 0) {
        resultsElt.animate(
          [
            { 'transform': 'translateY(-10%)' }
          ],
          {
            duration: 250,
            iterations: 1
          })
        Promise.all(resultsElt.getAnimations().map((animation) => animation.finished)).then(
          () => window.location.href = '/album/ajouter/' + resultsElt.dataset.albumtype + '/' + resultsElt.dataset.albumid
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
  const suggestBtns = document.querySelectorAll('.results .action')
  suggestBtns.forEach(btn => {
    btn.addEventListener('click', event => {
      event.preventDefault()
      if (event.target.parentNode.parentNode.classList.contains('top') || event.target.parentNode.classList.contains('top') || event.target.classList.contains('top')) {
        resultsElt.animate(
          [
            { 'transform': 'translateY(-10%)' }
          ],
          {
            duration: 250,
            iterations: 1
          })
        Promise.all(resultsElt.getAnimations().map((animation) => animation.finished)).then(
          () => window.location.href = '/album/ajouter/' + resultsElt.dataset.albumtype + '/' + resultsElt.dataset.albumid
        )
      }
    })
  })
  /* < CLICK */
})
