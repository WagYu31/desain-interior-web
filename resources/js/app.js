import './bootstrap';
import Alpine from 'alpinejs';
import AOS from 'aos';
import 'aos/dist/aos.css';
import * as bootstrap from 'bootstrap';
import { requestFirebasePermission } from './firebase';

//================================================
// INISIALISASI LIBRARY GLOBAL
//================================================
window.bootstrap = bootstrap;
window.Alpine = Alpine;
Alpine.start();
AOS.init({ duration: 800, once: true });
import.meta.glob(['../images/**']);

//================================================
// FUNGSI-FUNGSI HELPER UI (Bagian Notifikasi Dirombak)
//================================================

// Fungsi ini tidak berubah
function showToastrNotification(type, message, title = null, url = null) {
    if (typeof window.toastr === 'undefined') {
        console.error('Toastr library is not loaded!');
        alert((title ? title + ': ' : '') + message);
        return;
    }
    window.toastr.options.onclick = url ? function () { window.location.href = url; } : null;
    window.toastr[type](message, title || '');
}

// Variabel dan elemen UI Notifikasi
let notificationCount = window.initialUnreadNotifications || 0;
const navbarNotificationCountEls = document.querySelectorAll('#navbar-notification-count, #navbar-notification-count-mobile');
const notificationListEls = document.querySelectorAll('#notification-list, #notification-list-mobile');
const markAsReadUrl = document.querySelector('meta[name="mark-as-read-url"]')?.getAttribute('content');

/**
 * FUNGSI 1: Update badge angka di icon lonceng.
 */
function updateAllNotificationBadgesDisplay() {
    navbarNotificationCountEls.forEach(el => {
        if (el) {
            el.innerText = notificationCount > 0 ? notificationCount : '';
            el.style.display = notificationCount > 0 ? 'inline-block' : 'none';
        }
    });
}

/**
 * FUNGSI 2: Fungsi UTAMA untuk merender seluruh daftar dropdown dari data server.
 * @param {Array} notifications - Array objek notifikasi dari server.
 */
function renderNotificationDropdown(notifications) {
    notificationListEls.forEach(listEl => {
        if (!listEl) return;

        listEl.innerHTML = '';

        if (!notifications || notifications.length === 0) {
            listEl.innerHTML = '<li><a class="dropdown-item text-center text-muted py-3" href="#">Tidak ada notifikasi</a></li>';
            return;
        }

        notifications.forEach(notification => {
            const isUnread = notification.read_at === null;
            const listItem = document.createElement('li');

            const link = document.createElement('a');
            link.classList.add('dropdown-item', 'py-2', 'd-flex', 'align-items-center');
            link.href = notification.data.link_url || '#';

            if (isUnread) {
                link.classList.add('unread-notification');
            } else {
                link.classList.add('read-notification');
            }

            link.innerHTML = `
                ${isUnread ? '<span class="notification-dot me-2"></span>' : '<span class="notification-dot-placeholder me-2"></span>'}
                <div>
                    <div class="fw-bold">${notification.data.title || 'Notifikasi'}</div>
                    <small class="text-muted">${notification.data.message || notification.data.body}</small>
                </div>
            `;

            listItem.appendChild(link);
            listEl.appendChild(listItem);
        });
    });
}

/**
 * FUNGSI 3: Mengambil data notifikasi terbaru dari server dan me-render ulang dropdown.
 */
function fetchAndRenderNotifications() {
    axios.get('/notifications/latest')
        .then(response => {
            if (response.data.notifications) {
                renderNotificationDropdown(response.data.notifications);
            }
        })
        .catch(error => console.error('Gagal mengambil notifikasi:', error));
}

/**
 * FUNGSI 4: Menandai semua notifikasi sebagai sudah dibaca.
 */
function markNotificationsAsRead() {
    if (!markAsReadUrl || notificationCount === 0) return;

    notificationCount = 0;
    updateAllNotificationBadgesDisplay();

    axios.post(markAsReadUrl)
        .then(response => {
            if (response.data.status === 'success') {
                console.log('Notifikasi berhasil ditandai sebagai sudah dibaca di server.');
                fetchAndRenderNotifications();
            }
        })
        .catch(error => {
            console.error('Gagal menandai notifikasi sebagai sudah dibaca:', error);
        });
}

//======================================================================
//          EVENT LISTENER UTAMA (SETELAH DOM SIAP)
//======================================================================
document.addEventListener('DOMContentLoaded', function () {

    // =====================================================
    // NAVBAR TRANSPARENT → SCROLLED
    // =====================================================
    const navbar = document.querySelector('.navbar.fixed-top');
    if (navbar) {
        const handleNavbarScroll = () => {
            if (window.scrollY > 50) {
                navbar.classList.add('scrolled');
                navbar.classList.remove('navbar-transparent');
            } else {
                navbar.classList.remove('scrolled');
                navbar.classList.add('navbar-transparent');
            }
        };
        handleNavbarScroll();
        window.addEventListener('scroll', handleNavbarScroll);
    }

    // =====================================================
    // ✅ FIX: Bootstrap Dropdown Tidak Muncul
    // =====================================================
    document.querySelectorAll('[data-bs-toggle="dropdown"]').forEach(el => {
        new bootstrap.Dropdown(el);
    });


    // =====================================================
    // ADMIN SIDEBAR TOGGLE
    // =====================================================
    const sidebar = document.getElementById('adminSidebar');
    const sidebarToggle = document.getElementById('sidebarToggle');
    const sidebarOverlay = document.getElementById('sidebarOverlay');
    if (sidebarToggle && sidebar && sidebarOverlay) {
        const toggleSidebar = () => {
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        };
        sidebarToggle.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', toggleSidebar);
    }

    // =====================================================
    // MODAL PEMBATALAN PESANAN
    // =====================================================
    const cancelOrderModal = document.getElementById('cancelOrderModal');
    if (cancelOrderModal) {
        const baseActionUrl = cancelOrderModal.dataset.baseAction;
        cancelOrderModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const orderId = button.getAttribute('data-order-id');
            const actionUrl = baseActionUrl.replace(':orderId', orderId);
            const cancelForm = document.getElementById('cancelOrderForm');
            cancelForm.action = actionUrl;
            document.getElementById('cancellation_reason').value = '';
        });
    }

    // =====================================================
    // WHATSAPP FLOATING CHAT
    // =====================================================
    const fab = document.getElementById('whatsapp-fab');
    const chatbox = document.getElementById('whatsapp-chatbox');
    if (fab && chatbox) {
        const closeBtn = document.getElementById('chatbox-close');
        const sendBtn = document.getElementById('send-whatsapp-message');

        function toggleChatbox() {
            chatbox.classList.toggle('show');
        }

        fab.addEventListener('click', toggleChatbox);
        if (closeBtn) closeBtn.addEventListener('click', toggleChatbox);

        if (sendBtn) {
            const whatsappNumber = chatbox.dataset.whatsappNumber;
            const defaultMessage = chatbox.dataset.defaultMessage;

            if (whatsappNumber && defaultMessage) {
                sendBtn.href = `https://wa.me/${whatsappNumber}?text=${encodeURIComponent(defaultMessage)}`;
            } else {
                fab.style.display = 'none';
            }
        }
    }

    // =====================================================
    // PUSH NOTIFICATION (HANYA MENGGUNAKAN FCM)
    // =====================================================
    const userIdElement = document.querySelector('meta[name="user-id"]');

    if (userIdElement) {
        requestFirebasePermission();

        updateAllNotificationBadgesDisplay();

        fetchAndRenderNotifications();

        const notificationDropdownTriggers = document.querySelectorAll('#navbarNotificationDropdown, #navbarNotificationDropdownMobile');
        notificationDropdownTriggers.forEach(trigger => {
            if (trigger && trigger.parentElement) {
                trigger.parentElement.addEventListener('shown.bs.dropdown', markNotificationsAsRead);
            }
        });

        document.addEventListener('fcm-foreground-message', function (event) {
            console.log('%cFCM Foreground Message DITERIMA!', 'color: green; font-weight: bold;', event.detail);

            const payload = event.detail;
            const notificationData = payload.data;

            showToastrNotification(
                'info',
                notificationData.body,
                notificationData.title,
                notificationData.click_action
            );

            notificationCount++;
            updateAllNotificationBadgesDisplay();

            fetchAndRenderNotifications();
        });
    } else {
        console.warn('User not authenticated. Notifications disabled.');
    }
});