function addZero(i) {
    if (i < 10) {
        i = "0" + i
    }
    return i
}

let timeLimitSeconds = 20
let timer = document.getElementById("timer")

function countdown() {
    timeLimitSeconds--
    let seconds = timeLimitSeconds % 60

    if (timeLimitSeconds < 6) {
        timer.classList.add("red")
    }

    if (timeLimitSeconds === 0) {
        timer.textContent = "00:00"
        clearInterval(timerInterval)
        window.location.href = "playQuiz.php?timer_expired=1"
        return 
    }

    timer.textContent = "00:" + addZero(seconds)
}

let timerInterval = setInterval(countdown, 1000)