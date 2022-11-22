window.addEventListener('DOMContentLoaded', () => {
  /* SWIPE > */
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
        window.alert('Autre proposition')
        window.location.reload()
      } else {
        window.alert('Modification')
      }
    } else {
      if (yDiff > 0) {
        if (window.confirm('On écoute Animals ?')) {
          window.alert('Écoute + Autre proposition')
          window.location.reload()
        }
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
  const suggestBtns = document.querySelectorAll('.suggestion > .action')
  suggestBtns.forEach(btn => {
    btn.addEventListener('click', event => {
      event.preventDefault()
      if (event.target.parentNode.parentNode.classList.contains('top') || event.target.parentNode.classList.contains('top') || event.target.classList.contains('top')) {
        if (window.confirm('On écoute Animals ?')) {
          window.alert('Écoute + Autre proposition')
          window.location.reload()
        }
      } else if (event.target.parentNode.parentNode.classList.contains('left') || event.target.parentNode.classList.contains('left') || event.target.classList.contains('left')) {
        window.alert('Modification')
      } else if (event.target.parentNode.parentNode.classList.contains('right') || event.target.parentNode.classList.contains('right') || event.target.classList.contains('right')) {
        window.alert('Autre proposition')
        window.location.reload()
      }
    })
  })
  /* < CLICK */
})
