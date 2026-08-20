const swalEvents = ['swal', 'swal:error', 'swal:success', 'swal:info', 'swal:warning'];

swalEvents.forEach((eventName) => {
    window.addEventListener(eventName, (event) => {
        const data = event.detail?.[0] ?? {};

        let defaultIcon = 'info';
        if (eventName === 'swal:error') defaultIcon = 'error';
        if (eventName === 'swal:success') defaultIcon = 'success';
        if (eventName === 'swal:warning') defaultIcon = 'warning';

        window.Swal.fire({
            title: data.title ?? 'Aviso',
            text: data.text ?? '',
            icon: data.icon ?? defaultIcon,
            timer: data.timer ?? null,
            showConfirmButton: data.showConfirmButton ?? true,
            confirmButtonText: data.confirmButtonText ?? 'OK',
        }).then(() => {
            if (data.redirectUrl) {
                window.location.href = data.redirectUrl;
            }
        });
    });
});

window.addEventListener('swal:confirm', (event) => {
    const data = event.detail?.[0] ?? {};

    window.Swal.fire({
        title: data.title ?? 'Tem certeza?',
        text: data.text ?? '',
        icon: data.icon ?? 'warning',
        showCancelButton: true,
        confirmButtonText: data.confirmButtonText ?? 'Confirmar',
        cancelButtonText: data.cancelButtonText ?? 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed && data.confirmEvent && typeof Livewire !== 'undefined') {
            Livewire.dispatch(data.confirmEvent, data.confirmParams ?? []);
        }
    });
});