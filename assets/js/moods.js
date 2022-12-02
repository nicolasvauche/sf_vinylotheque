window.addEventListener('DOMContentLoaded', () => {
  const moods = [
    'fa-face-smile',
    'fa-face-grin-stars',
    'fa-face-kiss-wink-heart',
    'fa-face-sad-tear',
    'fa-face-angry',
    'fa-face-meh-blank'
  ]

  const moodPushesElt = document.querySelector(('.app-push.mood'))
  const currentMood = moodPushesElt.querySelector('i:first-child').classList[1]
  const index = moods.indexOf(currentMood)
  if (index > -1) {
    moods.splice(index, 1)
  }

  moods.forEach((mood, index) => {
    const moodElt = document.createElement('i')
    moodElt.classList.add('fa-solid')
    moodElt.classList.add(mood)
    moodElt.addEventListener('click', e => {
      console.log(e.target.classList[1])
      window.location.href = '/mood/' + e.target.classList[1]
    })
    moodPushesElt.querySelector('.moods').appendChild(moodElt)
  })
})
