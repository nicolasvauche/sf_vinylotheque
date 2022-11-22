window.addEventListener('DOMContentLoaded', () => {
  document.addEventListener('touchstart', handleTouchStart, false)
  document.addEventListener('touchmove', handleTouchMove, false)

  var xDown = null
  var yDown = null

  function getTouches (evt) {
    return evt.touches ||             // browser API
      evt.originalEvent.touches // jQuery
  }

  function handleTouchStart (evt) {
    const firstTouch = getTouches(evt)[0]
    xDown = firstTouch.clientX
    yDown = firstTouch.clientY
  }

  function handleTouchMove (evt) {
    if (!xDown || !yDown) {
      return
    }

    var xUp = evt.touches[0].clientX
    var yUp = evt.touches[0].clientY

    var xDiff = xDown - xUp
    var yDiff = yDown - yUp

    if (Math.abs(xDiff) > Math.abs(yDiff)) {/*most significant*/
      if (xDiff > 0) {
        window.location.reload()
        console.log('right')
      } else {
        console.log('left')
        window.alert('Modification')
      }
    } else {
      if (yDiff > 0) {
        console.log('up')
        if (window.confirm('On écoute ça !')) {
          window.location.reload()
        }
      } else {
        console.log('swiping down does nothing')
      }
    }
    /* reset values */
    xDown = null
    yDown = null
  }

})
