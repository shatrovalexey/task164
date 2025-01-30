(
	win => {
		const {"document": doc, "location": loc,} = win;
		const table = doc.querySelector("#main-table tfoot");
		const rowExample = doc.querySelector("tr.template");
		const tbody = doc.querySelector("#main-table tbody");
		const href = new URL("/api.php", loc.origin);
		const handle_events = node => {
			node.querySelectorAll(".button-add")
				.forEach(button => button.addEventListener("click", evt => {
					const form = button.closest("tr");
					const fdh = new FormData();

					["name", "email", "phone",]
						.forEach(inputName => fdh.set(inputName, form.querySelector(`[name="${inputName}"]`).value));
					fdh.set("action", "create");

					fetch(href, {
						"method": "POST"
						, "body": fdh,
					}).then(data => {
						form.reset();
						loc.reload();
					})
					.catch(error => console.log(error))
				}));

			node.querySelectorAll(".button-update")
				.forEach(button => button.addEventListener("click", evt => {
					const form = evt.target.closest("tr");
					const fdh = new FormData();

					["name", "email", "phone",]
						.forEach(inputName => fdh.set(inputName, form.querySelector(`[name="${inputName}"]`).value));
					fdh.set("action", "update");

					fetch(href, {
						"method": "POST"
						, "body": fdh,
					})
					.then(data => loc.reload());
				}));

			node.querySelectorAll(".button-delete")
				.forEach(button => button.addEventListener("click", evt => {
					const form = evt.target.closest("tr");
					const fdh = new FormData();

					fdh.set("email", form.querySelector("[name='email']").value);
					fdh.set("action", "delete");

					fetch(href, {
						"method": "POST"
						, "body": fdh,
					})
					.then(data => loc.reload());
				}));
		};

		handle_events(doc);

		fetch(href)
			.then(data => data.json())
			.then(data => {
				if (!("data" in data)) throw "Какая-то ошибка";

				Object(data.data).entries().forEach(([, values,]) => {
					const rowCloned = rowExample.cloneNode(true);

					rowCloned.classList.remove("template");

					for (const key in values) {
						const node = rowCloned.querySelector(`[data-value="${key}"]`);

						if (!node) continue;

						node.value = values[key];
					}

					tbody.appendChild(rowCloned);

					handle_events(rowCloned);
				});
			});
	}
)(window);