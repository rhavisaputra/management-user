console.log('Test user')

const user = document.getElementById('user')

if (user) {
	user.addEventListener('click', (e) => {
		if (e.target.id === 'delete-user') {
			if (confirm("Are you sure?")) {
				let userId = e.target.getAttribute('data-id')

				// API FETCH..
				fetch(`/user/delete/${userId}`, {
					method: 'DELETE'
				}).then(res => {
					window.location.reload()
				}).catch(e => {
					console.log('e:', e)
				})
			}
		}
	})
}