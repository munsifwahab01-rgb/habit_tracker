function filterHabits(status) {

    const habits = document.querySelectorAll(".habit-card");

    habits.forEach(function(habit) {

        if (
            status === "all" ||
            habit.dataset.status === status
        ) {
            habit.style.display = "block";
        } else {
            habit.style.display = "none";
        }

    });

}