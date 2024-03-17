function increaseCounter(counter) {
	let execute_again = false;

	let total_steps = counter.dataset.steps ? parseInt( counter.dataset.steps ) : 50;
	let current_step = counter.dataset.current_step ? parseInt( counter.dataset.current_step ) : 0;
	let count_to = parseInt( counter.dataset.count );

	if (current_step < total_steps) {
		let count_per_step = count_to / total_steps;
		let current_count = Math.floor((current_step + 1) * count_per_step);

		counter.dataset.current_step = current_step + 1;

		counter.textContent = current_count;

		execute_again = true;
	}

	if (execute_again) {
		setTimeout(increaseCounter.bind(this, counter), 10);
	}
}

function startCounters() {
	let observer = new IntersectionObserver(
		(entries, observer) => {
			entries.forEach(entry => {
				if (entry.isIntersecting) {
					increaseCounter(entry.target);
					observer.unobserve(entry.target);
				}
			});
		},
		{threshold: [1]});
	document.querySelectorAll('.widcol-counter-value').forEach(counter => { observer.observe(counter) });
}

window.onload = startCounters;