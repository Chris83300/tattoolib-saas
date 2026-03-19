<?php

app('router')->setCompiledRoutes(
    array (
  'compiled' => 
  array (
    0 => false,
    1 => 
    array (
      '/admin/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.auth.login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.auth.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.pages.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/refunds-page' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.pages.refunds-page',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/support-chat' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.pages.support-chat',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.appointments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/booking-requests' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.booking-requests.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/booking-requests/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.booking-requests.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/cancellations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.cancellations.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/complaints' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.complaints.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/complaints/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.complaints.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/compliance-records' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.compliance-records.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/compliance-records/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.compliance-records.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/conversations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.conversations.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data-processing-records' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.data-processing-records.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/data-processing-records/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.data-processing-records.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.payments.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/payments/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.payments.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/pierceurs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.pierceurs.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/pierceurs/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.pierceurs.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/reviews' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.reviews.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/reviews/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.reviews.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/studio-artists' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.studio-artists.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/studio-artists/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.studio-artists.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/studios' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.studios.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/studios/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.studios.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/subscriptions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.subscriptions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/tattooers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.tattooers.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/tattooers/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.tattooers.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/transactions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.transactions.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/transactions/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.transactions.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/users' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.users.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/users/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.users.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/shop/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.shop.auth.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/shop' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.shop.pages.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/studio/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.studio.auth.login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/studio/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.studio.auth.logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/studio' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.studio.pages.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/studio/booking-requests' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.studio.resources.booking-requests.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/admin/studio/studio-artists' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.studio.resources.studio-artists.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/stripe/webhook' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cashier.webhook',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'login.authenticate',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'logout',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/forgot-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.request',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'password.email',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'register.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/email/verify' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'verification.notice',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/email/verification-notification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'verification.send',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/confirm-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.confirm',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'password.confirm.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/confirmed-password-status' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.confirmation',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/two-factor-challenge' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'two-factor.login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'two-factor.login.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/two-factor-authentication' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'two-factor.enable',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'two-factor.disable',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/confirmed-two-factor-authentication' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'two-factor.confirm',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/two-factor-qr-code' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'two-factor.qr-code',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/two-factor-secret-key' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'two-factor.secret-key',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/user/two-factor-recovery-codes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'two-factor.recovery-codes',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'two-factor.regenerate-recovery-codes',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/sanctum/csrf-cookie' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'sanctum.csrf-cookie',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/flux/flux.js' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4hZCG3eHNANlCp25',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/flux/flux.min.js' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::IjuRDKxEFyQnlPvl',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/flux/editor.css' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5ZSd8wOMkfJb1QJv',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/flux/editor.js' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::NMWjlBireFPQ6ypn',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/flux/editor.min.js' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::U2pd1ooeiHPPmuay',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/livewire/livewire.js' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0oUHp4awVti1VRwk',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/livewire/livewire.min.js.map' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LBKwt1H3my40qBxc',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/livewire/upload-file' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'livewire.upload-file',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/register' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zV2XcdYRDNziABZL',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::y15g35ObL4DM2MQZ',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/tattooers' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::RE1Lv90aoWdSjYYB',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/marketplace/search' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::CP8nlRbSQITC5IKC',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/marketplace/suggestions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::om93qw6bZvShyuEv',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/marketplace/featured' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::hlagrdE8oHEYLzoG',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/marketplace/filters' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Y6tTDIovoLHhI2AB',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/marketplace/stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zrqTSMTYG5p129fI',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/user' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::VzV4ap1f0OMWwWFW',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/logout' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8aticEo8h7vCZmI4',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/fcm-token' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::KddILVfwH8av9Sxg',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/conversations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Dlo9oGZVg9aoBTNp',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/conversations/archived' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::of65P6S6y1UVLrfh',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/booking-requests' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::lDrKe2R7TdKU2EzT',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::M7HRDItwC9gTqXcU',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/booking-requests/statistics' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LxfEhhVc04BkojLp',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/bookings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5febar9bOFeQilbB',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::V8vpM4YkHF4Enc8E',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/appointments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LAKDsSxAHZSUISgd',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/appointments/upcoming' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::fWevUqodJ5qa1H57',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/appointments/past' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::8FnAJtGkpABVzHfS',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/appointments/statistics' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::CGQS1HdSvPdpuvf1',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/appointments/require-confirmation' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BWNRUZ1ciVeEYQA6',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/appointments/calendar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::b1vMFRLn9syCo5kd',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/availabilities' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::vx2mcNZ0Fqtysfpv',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aS6ulyQp9SOs2yNz',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/planning/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qjw0ioMAPuBj07V5',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/planning/block-slot' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::RxILwxvxN29IzORg',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/planning/create-external-appointment' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::t8SpZR3oSiBPUaFn',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/care-sheets' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::i9GkLzeMuGv1eVUz',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eqhH7ZHmgXrjY2hk',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/care-sheets/my-sheets' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ITyiAwfHZjRZlEfX',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/care-sheets/statistics' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WWeBX4CaNSzI74xx',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/inventory' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Qsa3BOvGApVYQTI4',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1KCO6AormAY6oDoP',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/inventory/statistics' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YUZtQTI2SKXtQSFM',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/inventory/alerts' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HfuonHukgk5MLSFD',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/accounting/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5GsviV5RcLIIoV4m',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/accounting/report' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cwqdcum1f4p2cmIp',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/accounting/appointment-stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::KDigcj8V8fZA9cBy',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/accounting/export' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::EaVD3C74XtyWcofg',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/accounting/transactions' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FVyUKTC5jK9z0ZKX',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eFSMwUGsE9Q5ASvj',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/messages/unread-count' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cLa8OFBrSbmXLeL8',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/traceability/consent-forms' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::voyKGrdyGKX2SoJV',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Q6OAbCBPjSy9udDQ',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/traceability/records' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::G8dTsAjBhVNsiomL',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::mw4eyxMrtPDokD0R',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/api/traceability/statistics' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cB4avp1XpHne4hkQ',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/up' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::iRneuOILvGo76Ab8',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'home',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/professionnels' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'professionnels.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tarifs' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pricing',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/marketplace' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'marketplace.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/profil' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/requests' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.requests',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/pricing' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.pricing',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/calendar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.calendar',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.calendar.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/calendar/events' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.calendar.events',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/messages' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.messages',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/clients' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.clients',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.clients.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/clients/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.clients.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/portfolio' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.portfolio',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/portfolio/upload' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.portfolio.upload',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/portfolio/before-after/store' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.portfolio.before-after.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.settings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.settings.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/settings/aftercare' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.settings.aftercare',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/settings/pricing' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.settings.pricing',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/settings/avatar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.settings.delete-avatar',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/settings/banner' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.settings.delete-banner',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/settings/schedule' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.settings.update-schedule',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/settings/password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.settings.update-password',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/settings/hours' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.settings.hours.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/settings/export-gdpr' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.gdpr.export',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.payments',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/stripe/connect' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.stripe.connect',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/subscription-plans' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.subscription.plans',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/subscribe' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.subscription.subscribe',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/subscription/success' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.subscription.success',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/subscription/cancel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.subscription.cancel',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/subscription/resume' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.subscription.resume',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/subscription/manage' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.subscription.manage',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/compliance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.compliance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/compliance/documents' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.compliance.documents',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.compliance.documents.upload',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/profil/edit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.profile.edit',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/portfolio-livewire' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.portfolio.livewire',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/disponibilites' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.availability',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/demandes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.demandes',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/messages-livewire' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.messages.livewire',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/reservations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.bookings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/clients-livewire' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.clients.livewire',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/statistiques' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.analytics',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/parametres' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.settings.livewire',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/tattooer/pending-verification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.pending-verification',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/register/plan' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register.plan',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/register/client' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register.client',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'register.client.submit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/register/tattooer' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register.tattooer',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'register.tattooer.submit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/register/pierceur' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register.pierceur',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'register.pierceur.submit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/register/studio' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'register.studio',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'register.studio.submit',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/client' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/client/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/client/booking-requests' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.booking-requests',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/client/reviews' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.reviews',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/client/complaints' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.complaints',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'client.complaints.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/profil' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/requests' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.requests',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/calendar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.calendar',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.calendar.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/calendar/events' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.calendar.events',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/messages' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.messages',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/clients' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.clients',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.clients.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/clients/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.clients.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/subscription-plans' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.subscription.plans',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/subscribe' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.subscription.subscribe',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/subscription/success' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.subscription.success',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/subscription/cancel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.subscription.cancel',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/subscription/resume' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.subscription.resume',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/subscription/manage' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.subscription.manage',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/subscribe-from-trial' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.subscription.subscribeFromTrial',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/portfolio' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.portfolio',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/portfolio/upload' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.portfolio.upload',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.settings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.settings.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/settings/aftercare' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.settings.aftercare',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/settings/pricing' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.settings.pricing',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/settings/avatar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.settings.delete-avatar',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/settings/banner' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.settings.delete-banner',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/settings/schedule' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.settings.update-schedule',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/settings/password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.settings.update-password',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/settings/hours' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.settings.hours.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/settings/export-gdpr' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.gdpr.export',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/payments' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.payments',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/stripe/connect' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.stripe.connect',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/compliance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.compliance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/profil/edit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.profile.edit',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/disponibilites' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.availability',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/demandes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.demandes',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/reservations' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.bookings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/statistiques' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.analytics',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/messages-livewire' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.messages.livewire',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/profil' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/profil/edit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.profile.edit',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/messages' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.messages',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/parametres' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.settings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'studio.settings.update',
          ),
          1 => NULL,
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/calendar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.calendar',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/artists' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.artists',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'studio.artists.store',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/artists/create' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.artists.create',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/artists/invite' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.artists.invite',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/planning' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.planning',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/planning/events' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.planning.events',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/demandes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.requests',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/clients' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.clients.index',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/billing' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.billing',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/subscribe' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.subscribe',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'studio.subscribe.post',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/subscription/cancel' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.subscription.cancel',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/subscription/resume' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.subscription.resume',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/subscription/sync' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.subscription.sync',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/souscrire' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.subscribe.legacy',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'studio.subscribe.legacy.process',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/stats' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.stats',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/stripe/connect' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.stripe.connect',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/upgrade' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.upgrade',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/compliance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.compliance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio-artist/dashboard' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio-artist.dashboard',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio-artist/profil' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio-artist.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio-artist/profil/edit' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio-artist.profile.edit',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio-artist/demandes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio-artist.booking-requests',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio-artist/messages' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio-artist.messages',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio-artist/parametres' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio-artist.settings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio-artist/calendar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio-artist.calendar',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio-artist/upgrade' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio-artist.upgrade',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio-artist/compliance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio-artist.studio-artist.compliance',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/pending-verification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.pending-verification',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/pending-verification' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.pending-verification',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/client/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.profile',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/client/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.settings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/client/settings/export-gdpr' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.gdpr.export',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/client/settings/avatar' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.settings.update-avatar',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'client.settings.delete-avatar',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/client/messages' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.messages',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/client/bookings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.bookings',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/pierceur/delete-account' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.delete-account',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/delete-account' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.delete-account',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/client/delete-account' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.delete-account',
          ),
          1 => NULL,
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/webhooks/stripe' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'webhooks.stripe',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/artist/stripe/return' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.artist.stripe.return',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/artist/stripe/refresh' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.artist.stripe.refresh',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/stripe/return' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.stripe.return',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/studio/stripe/refresh' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.stripe.refresh',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/booking-request/success' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'booking-request.success',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/auth/login' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'auth.login',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/auth/forgot-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'auth.password.request',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'auth.password.email',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/auth/reset-password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'auth.password.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/legal/mentions-legales' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'legal.mentions-legales',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/legal/cgu' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'legal.cgu',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/legal/cgv-artistes' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'legal.cgv-artistes',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/legal/cgv-clients' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'legal.cgv-clients',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/legal/politique-de-confidentialite' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'legal.politique-confidentialite',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/legal/politique-de-cookies' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'legal.politique-cookies',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HUdKgbOwoS0oDiOE',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
            'POST' => 2,
            'PUT' => 3,
            'PATCH' => 4,
            'DELETE' => 5,
            'OPTIONS' => 6,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/settings/profile' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'profile.edit',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/settings/password' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'user-password.edit',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/settings/appearance' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'appearance.edit',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/settings/two-factor' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'two-factor.show',
          ),
          1 => NULL,
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      '/livewire/update' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'livewire.update',
          ),
          1 => NULL,
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
    ),
    2 => 
    array (
      0 => '{^(?|/filament/(?|exports/([^/]++)/download(*:45)|imports/([^/]++)/failed\\-rows/download(*:90))|/a(?|dmin/(?|booking\\-requests/([^/]++)/edit(*:142)|c(?|ancellations/([^/]++)(*:175)|o(?|mpl(?|aints/([^/]++)/edit(*:212)|iance\\-records/([^/]++)/edit(*:248))|nversation(?|s/([^/]++)(*:280)|/([^/]++)(*:297))))|data\\-processing\\-records/([^/]++)/edit(*:347)|p(?|ayments/([^/]++)(?|(*:378)|/edit(*:391))|ierceurs/([^/]++)/edit(*:422))|reviews/([^/]++)/edit(*:452)|studio(?|\\-artists/([^/]++)(?|(*:490)|/edit(*:503))|s/([^/]++)(?|(*:525)|/edit(*:538))|/studio\\-artists/([^/]++)/edit(*:577))|t(?|attooers/([^/]++)/edit(*:612)|ransactions/([^/]++)/edit(*:645))|users/([^/]++)/edit(*:673))|pi/(?|t(?|attooers/([^/]++)(?|(*:712)|/(?|portfolio(?|(*:736)|(*:744)|/([^/]++)(*:761))|availability(*:782)|working\\-hours(?|(*:807)|/([0-6])(*:823))))|raceability/(?|consent\\-forms/([^/]++)/(?|verify(*:882)|parental\\-consent(*:907))|records/([^/]++)/(?|materials(*:945)|photos(*:959)|verify(*:973)|report(*:987))))|c(?|onversations/([^/]++)(?|(*:1026)|/(?|m(?|ark\\-as\\-read(*:1056)|essages(?|(*:1075)|/(?|([^/]++)(*:1096)|search(*:1111))))|toggle\\-mute(*:1135)|archive(*:1151)|block(*:1165)|design\\-versions(*:1190)))|are\\-sheets/(?|([^/]++)(?|(*:1227)|/photos(*:1243))|appointments/([^/]++)/create(*:1281)))|messages/([^/]++)/download(*:1318)|booking(?|s/([^/]++)(?|/(?|payment/deposit(*:1369)|accept(*:1384)|reject(*:1399)|c(?|onfirm\\-appointment(*:1431)|ancel(*:1445)))|(*:1456))|\\-requests/(?|([^/]++)(?|(*:1491)|/(?|accept(*:1510)|reject(*:1525)|confirm\\-deposit(*:1550)|send\\-design(*:1571)))|check\\-expired(*:1596)|([^/]++)/c(?|onfirm\\-appointment(*:1637)|ancel(*:1651))))|a(?|ppointments/([^/]++)(?|(*:1690)|/(?|c(?|onfirm\\-completion(*:1725)|ancel(*:1739))|report\\-(?|no\\-show(*:1768)|issue(*:1782))|dispute\\-refund(*:1807)))|vailabilities/(?|([^/]++)(?|(*:1846))|generate\\-from\\-working\\-hours(*:1886)|check(*:1900))|ccounting/transactions/([^/]++)/mark\\-paid(*:1952))|planning/(?|release\\-slot/([^/]++)(*:1996)|tattooers/([^/]++)/(?|available\\-dates(*:2043)|slots\\-for\\-date(*:2068)))|inventory/([^/]++)(?|(*:2100)|/(?|movement(?|(*:2124)|s(*:2134))|adjust(*:2150))))|rtistes/([^/]++)(*:2178)|uth/reset\\-password/([^/]++)(*:2215))|/s(?|t(?|ripe/(?|payment/([^/]++)(*:2258)|connect/re(?|turn/([^/]++)(*:2293)|fresh/([^/]++)(*:2316)))|udio(?|s/([^/]++)(*:2344)|/(?|invitation/([^/]++)(?|(*:2379))|artists/([^/]++)(?|(*:2408)|/toggle(*:2424))|demandes/([^/]++)(*:2451)|clients/([^/]++)(?|(*:2479))))|orage/(.*)(*:2501))|alon/([^/]++)(*:2524))|/reset\\-password/([^/]++)(*:2559)|/email/verify/([^/]++)/([^/]++)(*:2599)|/livewire/preview\\-file/([^/]++)(*:2640)|/tattooer/(?|requests/([^/]++)(?|(*:2682)|/(?|accept(?|(*:2704))|reject(*:2720)|cancel(*:2735))|(*:2745))|booking(?|\\-requests/([^/]++)/(?|repropose\\-dates(*:2804)|complete(*:2821)|no\\-show(*:2838))|s/([^/]++)/balance/confirm\\-offline(*:2883))|c(?|alendar/([^/]++)(?|(*:2916))|lient(?|s/([^/]++)(?|(*:2947)|/(?|consent/(?|upload(*:2977)|store\\-digital(*:3000)|([^/]++)(*:3017))|traceability(*:3039)|photos/(?|upload(*:3064)|([^/]++)(*:3081))|notes(*:3096)|requests(*:3113)))|/([^/]++)/(?|photos/([^/]++)(*:3152)|media/([^/]++)(*:3175)))|o(?|nsent/([^/]++)(*:3204)|mpliance/documents/([^/]++)(?|/view/([^/]++)(*:3257)|(*:3266))))|message(?|s/([^/]++)(*:3298)|/([^/]++)/send(*:3321))|traceability/([^/]++)(*:3352)|appointments/([^/]++)/(?|complete(*:3394)|no\\-show(*:3411))|portfolio/(?|([^/]++)(*:3442)|before\\-after/([^/]++)/([^/]++)(*:3482))|([^/]++)(*:3500)|delete\\-account(*:3524))|/p(?|ierce(?|r/([^/]++)(*:3557)|ur/(?|requests/([^/]++)(?|(*:3592)|/(?|accept(?|(*:3614))|reject(*:3630)|cancel(*:3645))|(*:3655))|booking(?|\\-requests/([^/]++)/(?|repropose\\-dates(*:3714)|complete(*:3731)|no\\-show(*:3748))|s/([^/]++)/balance/confirm\\-offline(*:3793))|c(?|alendar/([^/]++)(?|(*:3826))|lient(?|s/([^/]++)(?|(*:3857)|/(?|consent/(?|upload(*:3887)|store\\-digital(*:3910)|([^/]++)(*:3927))|traceability(*:3949)|photos/(?|upload(*:3974)|([^/]++)(*:3991))|notes(*:4006)|requests(*:4023)))|/([^/]++)/(?|photos/([^/]++)(*:4062)|media/([^/]++)(*:4085)))|onsent/([^/]++)(*:4111))|message(?|s/([^/]++)(*:4141)|/([^/]++)/send(*:4164))|traceability/([^/]++)(*:4195)|appointments/([^/]++)/(?|complete(*:4237)|no\\-show(*:4254))|portfolio/([^/]++)(*:4282)))|df/(?|c(?|are\\-sheet/([^/]++)(*:4322)|onsent\\-form/([^/]++)(*:4352)|lient\\-summary/([^/]++)(*:4384))|parental\\-consent/([^/]++)(*:4420)|traceability/([^/]++)(*:4450)|receipt/([^/]++)(*:4475)))|/c(?|lient/(?|booking(?|\\-request(?|s/([^/]++)(?|(*:4535)|/(?|select\\-date(*:4560)|request\\-alternatives(*:4590)|cancel(*:4605)))|/([^/]++)/delete(*:4632))|s/([^/]++)/balance(?|(*:4663)|/(?|checkout(*:4684)|success(*:4700))))|c(?|hat/([^/]++)(*:4728)|omplaints/([^/]++)(*:4755))|message/([^/]++)/send(*:4786)|re(?|views/([^/]++)(*:4814)|quests/([^/]++)/cancel(*:4845)))|on(?|versation/([^/]++)/chat(*:4884)|sent/([^/]++)(*:4906)))|/deposit/([^/]++)/(?|p(?|ayment(*:4948)|rocess(*:4963))|success(*:4980)|cancel(*:4995))|/booking\\-request/(?|([0-9]+)/(tattooer|Piercer|piercer|studio-artist)(*:5075)|([^/]++)/deposit/request(*:5108)))/?$}sDu',
    ),
    3 => 
    array (
      45 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.exports.download',
          ),
          1 => 
          array (
            0 => 'export',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      90 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.imports.failed-rows.download',
          ),
          1 => 
          array (
            0 => 'import',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      142 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.booking-requests.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      175 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.cancellations.view',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      212 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.complaints.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      248 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.compliance-records.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      280 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.conversations.view',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      297 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'admin.conversation.show',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      347 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.data-processing-records.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      378 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.payments.view',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      391 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.payments.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      422 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.pierceurs.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      452 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.reviews.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      490 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.studio-artists.view',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      503 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.studio-artists.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      525 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.studios.view',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      538 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.studios.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      577 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.studio.resources.studio-artists.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      612 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.tattooers.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      645 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.transactions.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      673 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.resources.users.edit',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      712 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::y1xJiISsOWd4pni9',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      736 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4BqND8pklhoUBtrf',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      744 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::bYcXI3NP5B1T1634',
          ),
          1 => 
          array (
            0 => 'tattooer',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      761 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::xrfSV0JkiDnnGij6',
          ),
          1 => 
          array (
            0 => 'tattooer',
            1 => 'mediaId',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      782 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::FhCbHp6ThJ0hK1YC',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      807 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zd6HdISEc2dtxxk0',
          ),
          1 => 
          array (
            0 => 'tattooer',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wYpWGAitJL2pa2Bd',
          ),
          1 => 
          array (
            0 => 'tattooer',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      823 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::zJwY6aqloty2Xooy',
          ),
          1 => 
          array (
            0 => 'tattooer',
            1 => 'day',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      882 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wcfLzGLqIXC8HAbo',
          ),
          1 => 
          array (
            0 => 'consentForm',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      907 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::fX57re7FZlM7P5Av',
          ),
          1 => 
          array (
            0 => 'consentForm',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      945 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::g0R6ViaTjZX8qtSx',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      959 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cGeTt0su5igPs7vd',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      973 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rn6Ey339QGcWaDYI',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      987 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::OAO11TxaHbbouAyK',
          ),
          1 => 
          array (
            0 => 'record',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1026 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::hJcPG2lxH4Y8t34s',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1056 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ca0WnA0tVowHa7rc',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1075 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ZHNm3zJeIyuK0lpu',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ilSZ3pAWs8Bx70Vo',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1096 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Ki4mSvbq1TgMrCd9',
          ),
          1 => 
          array (
            0 => 'conversation',
            1 => 'message',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1111 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gPlzKgvfO5mpibPb',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1135 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Fb5rYZkkaDZdC7TX',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1151 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::B7PLZTL8d7QxV1Uc',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1165 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::falC1s86XJ9j9YbX',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1190 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wNbHu7tSU7htrJtU',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1227 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::iZwBYb4LAbg6JEIO',
          ),
          1 => 
          array (
            0 => 'careSheet',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0RN3NpVMqGkBQwz5',
          ),
          1 => 
          array (
            0 => 'careSheet',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1243 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2ZGG8ucebnUQB1Mu',
          ),
          1 => 
          array (
            0 => 'careSheet',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1281 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::RZXS1x40gcHQFoPC',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1318 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'messages.download',
          ),
          1 => 
          array (
            0 => 'message',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1369 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::AOFfqdgj1JXXVsd5',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1384 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1FLDDn5CIlH3tbbJ',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1399 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::hr5VzoEC82TySy97',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1431 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::O9T2AYdGN4EDByi8',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1445 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::RmOsy8vCEn4BlhNX',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1456 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0k72Bj7myGcEe6my',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1491 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::4XD9quApiq6IV65Z',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1510 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YkvUUQWCH6P5fpJa',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1525 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::qqhnmcvSoF5wtnBJ',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1550 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::uzN7r0YRO9ifFL6L',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1571 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0a3C2PrzOi0l9Y0p',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1596 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BmtqId8gJF3NguXw',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1637 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::B7MxWvGJ6obfmN1Z',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1651 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::D8mT8hqugHGcF6xo',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1690 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gG4PD2voFGoHBUa9',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1725 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Bc2W01Lj7I41USfz',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1739 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::WdCxkBF94a3sfqUv',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1768 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::igvaqu7lMMc8rZiZ',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1782 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::MUnHMCkAKHqtxhzt',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1807 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ZPfbHBnRPNvhKevn',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1846 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::51J0K9uREPxP0QpA',
          ),
          1 => 
          array (
            0 => 'availability',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::LL3tyA6WjPsNiZky',
          ),
          1 => 
          array (
            0 => 'availability',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      1886 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::KpD3CDjaUmlFwnQd',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1900 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5CS4PdOkuwuaQF1n',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1952 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5MRDu3aDOD3D2bsG',
          ),
          1 => 
          array (
            0 => 'transaction',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      1996 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::YXlc0kEVJBXUUhyD',
          ),
          1 => 
          array (
            0 => 'availability',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2043 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::KcyrDvioduexUkVc',
          ),
          1 => 
          array (
            0 => 'tattooerId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2068 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::NfE2EZFxGcXETnbg',
          ),
          1 => 
          array (
            0 => 'tattooerId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2100 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::U7ukjtXQjEKEvZB5',
          ),
          1 => 
          array (
            0 => 'item',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'generated::19Jj30uKFvmswZhm',
          ),
          1 => 
          array (
            0 => 'item',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2124 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::GhqfytunDFFfi48T',
          ),
          1 => 
          array (
            0 => 'item',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2134 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::bPChVjO3OxAJPnnE',
          ),
          1 => 
          array (
            0 => 'item',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2150 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::vi6rw5mE1Y19mixu',
          ),
          1 => 
          array (
            0 => 'item',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2178 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'marketplace.show.artist',
          ),
          1 => 
          array (
            0 => 'slug',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2215 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'auth.password.reset',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2258 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'cashier.payment',
          ),
          1 => 
          array (
            0 => 'id',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2293 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'stripe.connect.return',
          ),
          1 => 
          array (
            0 => 'artist',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2316 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'stripe.connect.refresh',
          ),
          1 => 
          array (
            0 => 'artist',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2344 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.public',
          ),
          1 => 
          array (
            0 => 'slug',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2379 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.invitation.accept',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'studio.invitation.process',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2408 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.artists.show',
          ),
          1 => 
          array (
            0 => 'studioArtist',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'studio.artists.remove',
          ),
          1 => 
          array (
            0 => 'studioArtist',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2424 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.artists.toggle',
          ),
          1 => 
          array (
            0 => 'studioArtist',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2451 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.demandes.show',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2479 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.clients.show',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'studio.clients.update',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2501 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'storage.local',
          ),
          1 => 
          array (
            0 => 'path',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2524 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'studio.public.show',
          ),
          1 => 
          array (
            0 => 'slug',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2559 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'password.reset',
          ),
          1 => 
          array (
            0 => 'token',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2599 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'verification.verify',
          ),
          1 => 
          array (
            0 => 'id',
            1 => 'hash',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2640 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'livewire.preview-file',
          ),
          1 => 
          array (
            0 => 'filename',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2682 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.request.show',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2704 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.request.accept.get',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.request.accept',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2720 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.request-reject',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2735 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.requests.cancel',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2745 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.requests.destroy',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2804 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.booking-requests.repropose-dates',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2821 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.booking-requests.complete',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2838 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.booking-requests.no-show',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2883 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.balance-payment.confirm-offline',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      2916 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.calendar.update',
          ),
          1 => 
          array (
            0 => 'event',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.calendar.destroy',
          ),
          1 => 
          array (
            0 => 'event',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2947 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.client.show',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.clients.update',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      2977 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.clients.consent.upload',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3000 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.clients.consent.store-digital',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3017 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.clients.consent.delete',
          ),
          1 => 
          array (
            0 => 'client',
            1 => 'media',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3039 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.clients.traceability.store',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3064 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.clients.photos.upload',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3081 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.clients.photos.delete',
          ),
          1 => 
          array (
            0 => 'client',
            1 => 'media',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3096 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.client.update-notes',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3113 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.client-requests',
          ),
          1 => 
          array (
            0 => 'clientId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3152 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.client.photos.upload',
          ),
          1 => 
          array (
            0 => 'client',
            1 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3175 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.client.media.delete',
          ),
          1 => 
          array (
            0 => 'client',
            1 => 'media',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3204 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.consent.store',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3257 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.compliance.documents.serve',
          ),
          1 => 
          array (
            0 => 'complianceRecord',
            1 => 'field',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3266 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.compliance.documents.delete',
          ),
          1 => 
          array (
            0 => 'complianceRecord',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3298 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.message.show',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3321 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.message.send',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3352 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.traceability.store',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3394 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.appointments.complete',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3411 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.appointments.no-show',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3442 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.portfolio.destroy',
          ),
          1 => 
          array (
            0 => 'media',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3482 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.portfolio.before-after.destroy',
          ),
          1 => 
          array (
            0 => 'beforeId',
            1 => 'afterId',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3500 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'marketplace.tattooer.show',
          ),
          1 => 
          array (
            0 => 'slug',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3524 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.delete-account',
          ),
          1 => 
          array (
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3557 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'marketplace.piercer.show',
          ),
          1 => 
          array (
            0 => 'slug',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3592 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.request.show',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3614 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.request.accept.get',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.request.accept',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3630 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.request-reject',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3645 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.requests.cancel',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3655 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.requests.destroy',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3714 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.booking-requests.repropose-dates',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3731 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.booking-requests.complete',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3748 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.booking-requests.no-show',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3793 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.balance-payment.confirm-offline',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3826 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.calendar.update',
          ),
          1 => 
          array (
            0 => 'event',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.calendar.destroy',
          ),
          1 => 
          array (
            0 => 'event',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3857 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.client.show',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
        1 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.clients.update',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'PUT' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3887 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.clients.consent.upload',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3910 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.clients.consent.store-digital',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3927 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.clients.consent.delete',
          ),
          1 => 
          array (
            0 => 'client',
            1 => 'media',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      3949 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.clients.traceability.store',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3974 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.clients.photos.upload',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      3991 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.clients.photos.delete',
          ),
          1 => 
          array (
            0 => 'client',
            1 => 'media',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4006 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.client.update-notes',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4023 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.client-requests',
          ),
          1 => 
          array (
            0 => 'clientId',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4062 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.client.photos.upload',
          ),
          1 => 
          array (
            0 => 'client',
            1 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4085 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.client.media.delete',
          ),
          1 => 
          array (
            0 => 'client',
            1 => 'media',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4111 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.consent.store',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4141 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.message.show',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4164 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.message.send',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4195 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.traceability.store',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4237 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.appointments.complete',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4254 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.appointments.no-show',
          ),
          1 => 
          array (
            0 => 'appointment',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4282 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pierceur.portfolio.destroy',
          ),
          1 => 
          array (
            0 => 'media',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4322 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pdf.care-sheet',
          ),
          1 => 
          array (
            0 => 'careSheet',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4352 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pdf.consent-form',
          ),
          1 => 
          array (
            0 => 'consentForm',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4384 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pdf.client-summary',
          ),
          1 => 
          array (
            0 => 'client',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4420 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pdf.parental-consent',
          ),
          1 => 
          array (
            0 => 'parentalConsent',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4450 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pdf.traceability',
          ),
          1 => 
          array (
            0 => 'traceabilityRecord',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4475 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'pdf.receipt',
          ),
          1 => 
          array (
            0 => 'booking',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4535 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.booking-request.show',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4560 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.booking-request.select-date',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4590 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.booking-request.request-alternatives',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4605 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.booking-request.cancel',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4632 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.booking-request.delete',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'DELETE' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4663 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.balance.show',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4684 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.balance-payment.checkout',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4700 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.balance-payment.success',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4728 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.chat',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4755 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.complaints.create',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4786 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.message.send',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4814 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.reviews.create',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4845 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'client.requests.cancel',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'PATCH' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4884 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'conversation.chat.show',
          ),
          1 => 
          array (
            0 => 'conversation',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4906 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'consent.store',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      4948 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'deposit.payment',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4963 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'deposit.process',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'POST' => 0,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4980 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'deposit.success',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      4995 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'deposit.cancel',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
      ),
      5075 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'booking-request.form',
          ),
          1 => 
          array (
            0 => 'bookableId',
            1 => 'bookableType',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => true,
          6 => NULL,
        ),
      ),
      5108 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'booking-request.deposit.request',
          ),
          1 => 
          array (
            0 => 'bookingRequest',
          ),
          2 => 
          array (
            'GET' => 0,
            'HEAD' => 1,
          ),
          3 => NULL,
          4 => false,
          5 => false,
          6 => NULL,
        ),
        1 => 
        array (
          0 => NULL,
          1 => NULL,
          2 => NULL,
          3 => NULL,
          4 => false,
          5 => false,
          6 => 0,
        ),
      ),
    ),
    4 => NULL,
  ),
  'attributes' => 
  array (
    'filament.exports.download' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'filament/exports/{export}/download',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'filament.actions',
        ),
        'uses' => 'Filament\\Actions\\Exports\\Http\\Controllers\\DownloadExport@__invoke',
        'controller' => 'Filament\\Actions\\Exports\\Http\\Controllers\\DownloadExport',
        'as' => 'filament.exports.download',
        'namespace' => NULL,
        'prefix' => 'filament',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.imports.failed-rows.download' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'filament/imports/{import}/failed-rows/download',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'filament.actions',
        ),
        'uses' => 'Filament\\Actions\\Imports\\Http\\Controllers\\DownloadImportFailureCsv@__invoke',
        'controller' => 'Filament\\Actions\\Imports\\Http\\Controllers\\DownloadImportFailureCsv',
        'as' => 'filament.imports.failed-rows.download',
        'namespace' => NULL,
        'prefix' => 'filament',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.auth.login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/login',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
        ),
        'uses' => 'Filament\\Auth\\Pages\\Login@__invoke',
        'controller' => 'Filament\\Auth\\Pages\\Login',
        'as' => 'filament.admin.auth.login',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.auth.logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/logout',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'uses' => 'Filament\\Auth\\Http\\Controllers\\LogoutController@__invoke',
        'controller' => 'Filament\\Auth\\Http\\Controllers\\LogoutController',
        'as' => 'filament.admin.auth.logout',
        'namespace' => NULL,
        'prefix' => '/admin',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.pages.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'uses' => 'Filament\\Pages\\Dashboard@__invoke',
        'controller' => 'Filament\\Pages\\Dashboard',
        'as' => 'filament.admin.pages.dashboard',
        'namespace' => NULL,
        'prefix' => 'admin/',
        'where' => 
        array (
        ),
        'excluded_middleware' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.pages.refunds-page' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/refunds-page',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'uses' => 'App\\Filament\\Admin\\Pages\\RefundsPage@__invoke',
        'controller' => 'App\\Filament\\Admin\\Pages\\RefundsPage',
        'as' => 'filament.admin.pages.refunds-page',
        'namespace' => NULL,
        'prefix' => 'admin/',
        'where' => 
        array (
        ),
        'excluded_middleware' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.pages.support-chat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/support-chat',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'uses' => 'App\\Filament\\Admin\\Pages\\SupportChat@__invoke',
        'controller' => 'App\\Filament\\Admin\\Pages\\SupportChat',
        'as' => 'filament.admin.pages.support-chat',
        'namespace' => NULL,
        'prefix' => 'admin/',
        'where' => 
        array (
        ),
        'excluded_middleware' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.appointments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/appointments',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Appointments\\Pages\\ManageAppointments@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Appointments\\Pages\\ManageAppointments',
        'as' => 'filament.admin.resources.appointments.index',
        'namespace' => NULL,
        'prefix' => 'admin/appointments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.booking-requests.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/booking-requests',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\BookingRequests\\Pages\\ListBookingRequests@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\BookingRequests\\Pages\\ListBookingRequests',
        'as' => 'filament.admin.resources.booking-requests.index',
        'namespace' => NULL,
        'prefix' => 'admin/booking-requests',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.booking-requests.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/booking-requests/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\BookingRequests\\Pages\\CreateBookingRequest@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\BookingRequests\\Pages\\CreateBookingRequest',
        'as' => 'filament.admin.resources.booking-requests.create',
        'namespace' => NULL,
        'prefix' => 'admin/booking-requests',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.booking-requests.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/booking-requests/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\BookingRequests\\Pages\\EditBookingRequest@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\BookingRequests\\Pages\\EditBookingRequest',
        'as' => 'filament.admin.resources.booking-requests.edit',
        'namespace' => NULL,
        'prefix' => 'admin/booking-requests',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.cancellations.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cancellations',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\CancellationResource\\Pages\\ListCancellations@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\CancellationResource\\Pages\\ListCancellations',
        'as' => 'filament.admin.resources.cancellations.index',
        'namespace' => NULL,
        'prefix' => 'admin/cancellations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.cancellations.view' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/cancellations/{record}',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\CancellationResource\\Pages\\ViewCancellation@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\CancellationResource\\Pages\\ViewCancellation',
        'as' => 'filament.admin.resources.cancellations.view',
        'namespace' => NULL,
        'prefix' => 'admin/cancellations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.complaints.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/complaints',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Complaints\\Pages\\ListComplaints@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Complaints\\Pages\\ListComplaints',
        'as' => 'filament.admin.resources.complaints.index',
        'namespace' => NULL,
        'prefix' => 'admin/complaints',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.complaints.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/complaints/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Complaints\\Pages\\CreateComplaint@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Complaints\\Pages\\CreateComplaint',
        'as' => 'filament.admin.resources.complaints.create',
        'namespace' => NULL,
        'prefix' => 'admin/complaints',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.complaints.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/complaints/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Complaints\\Pages\\EditComplaint@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Complaints\\Pages\\EditComplaint',
        'as' => 'filament.admin.resources.complaints.edit',
        'namespace' => NULL,
        'prefix' => 'admin/complaints',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.compliance-records.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/compliance-records',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\ComplianceRecords\\Pages\\ListComplianceRecords@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\ComplianceRecords\\Pages\\ListComplianceRecords',
        'as' => 'filament.admin.resources.compliance-records.index',
        'namespace' => NULL,
        'prefix' => 'admin/compliance-records',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.compliance-records.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/compliance-records/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\ComplianceRecords\\Pages\\CreateComplianceRecord@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\ComplianceRecords\\Pages\\CreateComplianceRecord',
        'as' => 'filament.admin.resources.compliance-records.create',
        'namespace' => NULL,
        'prefix' => 'admin/compliance-records',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.compliance-records.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/compliance-records/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\ComplianceRecords\\Pages\\EditComplianceRecord@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\ComplianceRecords\\Pages\\EditComplianceRecord',
        'as' => 'filament.admin.resources.compliance-records.edit',
        'namespace' => NULL,
        'prefix' => 'admin/compliance-records',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.conversations.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/conversations',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\ConversationResource\\Pages\\ListConversations@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\ConversationResource\\Pages\\ListConversations',
        'as' => 'filament.admin.resources.conversations.index',
        'namespace' => NULL,
        'prefix' => 'admin/conversations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.conversations.view' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/conversations/{record}',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\ConversationResource\\Pages\\ViewConversation@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\ConversationResource\\Pages\\ViewConversation',
        'as' => 'filament.admin.resources.conversations.view',
        'namespace' => NULL,
        'prefix' => 'admin/conversations',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.data-processing-records.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/data-processing-records',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\DataProcessingRecords\\Pages\\ListDataProcessingRecords@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\DataProcessingRecords\\Pages\\ListDataProcessingRecords',
        'as' => 'filament.admin.resources.data-processing-records.index',
        'namespace' => NULL,
        'prefix' => 'admin/data-processing-records',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.data-processing-records.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/data-processing-records/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\DataProcessingRecords\\Pages\\CreateDataProcessingRecord@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\DataProcessingRecords\\Pages\\CreateDataProcessingRecord',
        'as' => 'filament.admin.resources.data-processing-records.create',
        'namespace' => NULL,
        'prefix' => 'admin/data-processing-records',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.data-processing-records.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/data-processing-records/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\DataProcessingRecords\\Pages\\EditDataProcessingRecord@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\DataProcessingRecords\\Pages\\EditDataProcessingRecord',
        'as' => 'filament.admin.resources.data-processing-records.edit',
        'namespace' => NULL,
        'prefix' => 'admin/data-processing-records',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.payments.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/payments',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Payments\\Pages\\ListPayments@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Payments\\Pages\\ListPayments',
        'as' => 'filament.admin.resources.payments.index',
        'namespace' => NULL,
        'prefix' => 'admin/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.payments.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/payments/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Payments\\Pages\\CreatePayment@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Payments\\Pages\\CreatePayment',
        'as' => 'filament.admin.resources.payments.create',
        'namespace' => NULL,
        'prefix' => 'admin/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.payments.view' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/payments/{record}',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Payments\\Pages\\ViewPayment@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Payments\\Pages\\ViewPayment',
        'as' => 'filament.admin.resources.payments.view',
        'namespace' => NULL,
        'prefix' => 'admin/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.payments.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/payments/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Payments\\Pages\\EditPayment@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Payments\\Pages\\EditPayment',
        'as' => 'filament.admin.resources.payments.edit',
        'namespace' => NULL,
        'prefix' => 'admin/payments',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.pierceurs.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/pierceurs',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Pierceurs\\Pages\\ListPierceurs@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Pierceurs\\Pages\\ListPierceurs',
        'as' => 'filament.admin.resources.pierceurs.index',
        'namespace' => NULL,
        'prefix' => 'admin/pierceurs',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.pierceurs.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/pierceurs/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Pierceurs\\Pages\\CreatePierceur@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Pierceurs\\Pages\\CreatePierceur',
        'as' => 'filament.admin.resources.pierceurs.create',
        'namespace' => NULL,
        'prefix' => 'admin/pierceurs',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.pierceurs.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/pierceurs/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Pierceurs\\Pages\\EditPierceur@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Pierceurs\\Pages\\EditPierceur',
        'as' => 'filament.admin.resources.pierceurs.edit',
        'namespace' => NULL,
        'prefix' => 'admin/pierceurs',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.reviews.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/reviews',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Reviews\\Pages\\ListReviews@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Reviews\\Pages\\ListReviews',
        'as' => 'filament.admin.resources.reviews.index',
        'namespace' => NULL,
        'prefix' => 'admin/reviews',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.reviews.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/reviews/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Reviews\\Pages\\CreateReview@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Reviews\\Pages\\CreateReview',
        'as' => 'filament.admin.resources.reviews.create',
        'namespace' => NULL,
        'prefix' => 'admin/reviews',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.reviews.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/reviews/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Reviews\\Pages\\EditReview@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Reviews\\Pages\\EditReview',
        'as' => 'filament.admin.resources.reviews.edit',
        'namespace' => NULL,
        'prefix' => 'admin/reviews',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.studio-artists.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studio-artists',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\StudioArtists\\Pages\\ListStudioArtists@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\StudioArtists\\Pages\\ListStudioArtists',
        'as' => 'filament.admin.resources.studio-artists.index',
        'namespace' => NULL,
        'prefix' => 'admin/studio-artists',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.studio-artists.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studio-artists/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\StudioArtists\\Pages\\CreateStudioArtist@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\StudioArtists\\Pages\\CreateStudioArtist',
        'as' => 'filament.admin.resources.studio-artists.create',
        'namespace' => NULL,
        'prefix' => 'admin/studio-artists',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.studio-artists.view' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studio-artists/{record}',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\StudioArtists\\Pages\\ViewStudioArtist@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\StudioArtists\\Pages\\ViewStudioArtist',
        'as' => 'filament.admin.resources.studio-artists.view',
        'namespace' => NULL,
        'prefix' => 'admin/studio-artists',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.studio-artists.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studio-artists/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\StudioArtists\\Pages\\EditStudioArtist@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\StudioArtists\\Pages\\EditStudioArtist',
        'as' => 'filament.admin.resources.studio-artists.edit',
        'namespace' => NULL,
        'prefix' => 'admin/studio-artists',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.studios.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studios',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Studios\\Pages\\ListStudios@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Studios\\Pages\\ListStudios',
        'as' => 'filament.admin.resources.studios.index',
        'namespace' => NULL,
        'prefix' => 'admin/studios',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.studios.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studios/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Studios\\Pages\\CreateStudio@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Studios\\Pages\\CreateStudio',
        'as' => 'filament.admin.resources.studios.create',
        'namespace' => NULL,
        'prefix' => 'admin/studios',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.studios.view' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studios/{record}',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Studios\\Pages\\ViewStudio@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Studios\\Pages\\ViewStudio',
        'as' => 'filament.admin.resources.studios.view',
        'namespace' => NULL,
        'prefix' => 'admin/studios',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.studios.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studios/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Studios\\Pages\\EditStudio@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Studios\\Pages\\EditStudio',
        'as' => 'filament.admin.resources.studios.edit',
        'namespace' => NULL,
        'prefix' => 'admin/studios',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.subscriptions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/subscriptions',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Subscriptions\\Pages\\ManageSubscriptions@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Subscriptions\\Pages\\ManageSubscriptions',
        'as' => 'filament.admin.resources.subscriptions.index',
        'namespace' => NULL,
        'prefix' => 'admin/subscriptions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.tattooers.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/tattooers',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Tattooers\\Pages\\ListTattooers@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Tattooers\\Pages\\ListTattooers',
        'as' => 'filament.admin.resources.tattooers.index',
        'namespace' => NULL,
        'prefix' => 'admin/tattooers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.tattooers.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/tattooers/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Tattooers\\Pages\\CreateTattooer@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Tattooers\\Pages\\CreateTattooer',
        'as' => 'filament.admin.resources.tattooers.create',
        'namespace' => NULL,
        'prefix' => 'admin/tattooers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.tattooers.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/tattooers/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Tattooers\\Pages\\EditTattooer@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Tattooers\\Pages\\EditTattooer',
        'as' => 'filament.admin.resources.tattooers.edit',
        'namespace' => NULL,
        'prefix' => 'admin/tattooers',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.transactions.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/transactions',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Transactions\\Pages\\ListTransactions@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Transactions\\Pages\\ListTransactions',
        'as' => 'filament.admin.resources.transactions.index',
        'namespace' => NULL,
        'prefix' => 'admin/transactions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.transactions.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/transactions/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Transactions\\Pages\\CreateTransaction@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Transactions\\Pages\\CreateTransaction',
        'as' => 'filament.admin.resources.transactions.create',
        'namespace' => NULL,
        'prefix' => 'admin/transactions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.transactions.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/transactions/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Transactions\\Pages\\EditTransaction@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Transactions\\Pages\\EditTransaction',
        'as' => 'filament.admin.resources.transactions.edit',
        'namespace' => NULL,
        'prefix' => 'admin/transactions',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.users.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Users\\Pages\\ListUsers@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Users\\Pages\\ListUsers',
        'as' => 'filament.admin.resources.users.index',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.users.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users/create',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Users\\Pages\\CreateUser@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Users\\Pages\\CreateUser',
        'as' => 'filament.admin.resources.users.create',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.admin.resources.users.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/users/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:admin',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Admin\\Resources\\Users\\Pages\\EditUser@__invoke',
        'controller' => 'App\\Filament\\Admin\\Resources\\Users\\Pages\\EditUser',
        'as' => 'filament.admin.resources.users.edit',
        'namespace' => NULL,
        'prefix' => 'admin/users',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.shop.auth.logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'shop/logout',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:shop',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'uses' => 'Filament\\Auth\\Http\\Controllers\\LogoutController@__invoke',
        'controller' => 'Filament\\Auth\\Http\\Controllers\\LogoutController',
        'as' => 'filament.shop.auth.logout',
        'namespace' => NULL,
        'prefix' => '/shop',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.shop.pages.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'shop',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:shop',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
        ),
        'uses' => 'Filament\\Pages\\Dashboard@__invoke',
        'controller' => 'Filament\\Pages\\Dashboard',
        'as' => 'filament.shop.pages.dashboard',
        'namespace' => NULL,
        'prefix' => 'shop/',
        'where' => 
        array (
        ),
        'excluded_middleware' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.studio.auth.login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studio/login',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:studio',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
        ),
        'uses' => 'Filament\\Auth\\Pages\\Login@__invoke',
        'controller' => 'Filament\\Auth\\Pages\\Login',
        'as' => 'filament.studio.auth.login',
        'namespace' => NULL,
        'prefix' => '/admin/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.studio.auth.logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'admin/studio/logout',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:studio',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsStudio',
        ),
        'uses' => 'Filament\\Auth\\Http\\Controllers\\LogoutController@__invoke',
        'controller' => 'Filament\\Auth\\Http\\Controllers\\LogoutController',
        'as' => 'filament.studio.auth.logout',
        'namespace' => NULL,
        'prefix' => '/admin/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.studio.pages.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studio',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:studio',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsStudio',
        ),
        'uses' => 'App\\Filament\\Studio\\Pages\\Dashboard@__invoke',
        'controller' => 'App\\Filament\\Studio\\Pages\\Dashboard',
        'as' => 'filament.studio.pages.dashboard',
        'namespace' => NULL,
        'prefix' => 'admin/studio/',
        'where' => 
        array (
        ),
        'excluded_middleware' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.studio.resources.booking-requests.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studio/booking-requests',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:studio',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsStudio',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Studio\\Resources\\BookingRequestResource\\Pages\\ListBookingRequests@__invoke',
        'controller' => 'App\\Filament\\Studio\\Resources\\BookingRequestResource\\Pages\\ListBookingRequests',
        'as' => 'filament.studio.resources.booking-requests.index',
        'namespace' => NULL,
        'prefix' => 'admin/studio/booking-requests',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.studio.resources.studio-artists.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studio/studio-artists',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:studio',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsStudio',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Studio\\Resources\\StudioArtistResource\\Pages\\ListStudioArtists@__invoke',
        'controller' => 'App\\Filament\\Studio\\Resources\\StudioArtistResource\\Pages\\ListStudioArtists',
        'as' => 'filament.studio.resources.studio-artists.index',
        'namespace' => NULL,
        'prefix' => 'admin/studio/studio-artists',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'filament.studio.resources.studio-artists.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/studio/studio-artists/{record}/edit',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'panel:studio',
          1 => 'Illuminate\\Cookie\\Middleware\\EncryptCookies',
          2 => 'Illuminate\\Cookie\\Middleware\\AddQueuedCookiesToResponse',
          3 => 'Illuminate\\Session\\Middleware\\StartSession',
          4 => 'Filament\\Http\\Middleware\\AuthenticateSession',
          5 => 'Illuminate\\View\\Middleware\\ShareErrorsFromSession',
          6 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
          7 => 'Illuminate\\Routing\\Middleware\\SubstituteBindings',
          8 => 'Filament\\Http\\Middleware\\DisableBladeIconComponents',
          9 => 'Filament\\Http\\Middleware\\DispatchServingFilamentEvent',
          10 => 'Filament\\Http\\Middleware\\Authenticate',
          11 => 'App\\Http\\Middleware\\EnsureUserIsStudio',
        ),
        'excluded_middleware' => 
        array (
        ),
        'uses' => 'App\\Filament\\Studio\\Resources\\StudioArtistResource\\Pages\\EditStudioArtist@__invoke',
        'controller' => 'App\\Filament\\Studio\\Resources\\StudioArtistResource\\Pages\\EditStudioArtist',
        'as' => 'filament.studio.resources.studio-artists.edit',
        'namespace' => NULL,
        'prefix' => 'admin/studio/studio-artists',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cashier.payment' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'stripe/payment/{id}',
      'action' => 
      array (
        'uses' => 'Laravel\\Cashier\\Http\\Controllers\\PaymentController@show',
        'controller' => 'Laravel\\Cashier\\Http\\Controllers\\PaymentController@show',
        'as' => 'cashier.payment',
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'prefix' => 'stripe',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'cashier.webhook' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'stripe/webhook',
      'action' => 
      array (
        'uses' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController@handleWebhook',
        'controller' => 'Laravel\\Cashier\\Http\\Controllers\\WebhookController@handleWebhook',
        'as' => 'cashier.webhook',
        'namespace' => 'Laravel\\Cashier\\Http\\Controllers',
        'prefix' => 'stripe',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:803:"function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (\\auth()->check()) {
        $user = \\auth()->user();
        switch ($user->role) {
            case \'client\':
                return \\redirect()->route(\'client.profile\');
            case \'tattooer\':
                return \\redirect()->route(\'tattooer.profile\');
            case \'Piercer\':
            case \'pierceur\':
                return \\redirect()->route(\'pierceur.dashboard\');
            case \'studio\':
                return \\redirect()->route(\'studio.dashboard\');
            case \'studio_artist\':
                return \\redirect()->route(\'studio-artist.dashboard\');
            default:
                return \\redirect()->route(\'home\');
        }
    }
    return \\view(\'livewire.auth.login-simple\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011890000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'login.authenticate' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:login',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LoginController@authenticate',
        'controller' => 'App\\Http\\Controllers\\Auth\\LoginController@authenticate',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'login.authenticate',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'logout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\LogoutController@logout',
        'controller' => 'App\\Http\\Controllers\\Auth\\LogoutController@logout',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'logout',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'forgot-password',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\PasswordResetLinkController@create',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\PasswordResetLinkController@create',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.request',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.reset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'reset-password/{token}',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\NewPasswordController@create',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\NewPasswordController@create',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.reset',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.email' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'forgot-password',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\PasswordResetLinkController@store',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\PasswordResetLinkController@store',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.email',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'reset-password',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\NewPasswordController@store',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\NewPasswordController@store',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:585:"function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (\\auth()->check()) {
        $user = \\auth()->user();

        if ($user->role === \'client\') {
            return \\redirect()->route(\'client.profile\');
        } elseif ($user->role === \'studio\') {
            return \\redirect()->route(\'studio.dashboard\');
        } elseif (\\in_array($user->role, [\'tattooer\', \'piercer\', \'studio_artist\'])) {
            return \\redirect()->route(\'tattooer.dashboard\');
        }

        return \\redirect()->route(\'home\');
    }
    return \\view(\'auth.register\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011870000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'register',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\RegisteredUserController@store',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\RegisteredUserController@store',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'verification.notice' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'email/verify',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\EmailVerificationPromptController@__invoke',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\EmailVerificationPromptController@__invoke',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'verification.notice',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'verification.verify' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'email/verify/{id}/{hash}',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
          2 => 'signed',
          3 => 'throttle:6,1',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\VerifyEmailController@__invoke',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\VerifyEmailController@__invoke',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'verification.verify',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'verification.send' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'email/verification-notification',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
          2 => 'throttle:6,1',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\EmailVerificationNotificationController@store',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\EmailVerificationNotificationController@store',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'verification.send',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.confirm' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/confirm-password',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\ConfirmablePasswordController@show',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\ConfirmablePasswordController@show',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.confirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.confirmation' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/confirmed-password-status',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\ConfirmedPasswordStatusController@show',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\ConfirmedPasswordStatusController@show',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.confirmation',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'password.confirm.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/confirm-password',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\ConfirmablePasswordController@store',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\ConfirmablePasswordController@store',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'password.confirm.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'two-factor.login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'two-factor-challenge',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController@create',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController@create',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'two-factor.login',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'two-factor.login.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'two-factor-challenge',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'guest:web',
          2 => 'throttle:two-factor',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController@store',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticatedSessionController@store',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'two-factor.login.store',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'two-factor.enable' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/two-factor-authentication',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticationController@store',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticationController@store',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'two-factor.enable',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'two-factor.confirm' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/confirmed-two-factor-authentication',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\ConfirmedTwoFactorAuthenticationController@store',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\ConfirmedTwoFactorAuthenticationController@store',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'two-factor.confirm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'two-factor.disable' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'user/two-factor-authentication',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticationController@destroy',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorAuthenticationController@destroy',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'two-factor.disable',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'two-factor.qr-code' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/two-factor-qr-code',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorQrCodeController@show',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorQrCodeController@show',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'two-factor.qr-code',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'two-factor.secret-key' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/two-factor-secret-key',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorSecretKeyController@show',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\TwoFactorSecretKeyController@show',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'two-factor.secret-key',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'two-factor.recovery-codes' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'user/two-factor-recovery-codes',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\RecoveryCodeController@index',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\RecoveryCodeController@index',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'two-factor.recovery-codes',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'two-factor.regenerate-recovery-codes' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'user/two-factor-recovery-codes',
      'action' => 
      array (
        'domain' => NULL,
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth:web',
        ),
        'uses' => 'Laravel\\Fortify\\Http\\Controllers\\RecoveryCodeController@store',
        'controller' => 'Laravel\\Fortify\\Http\\Controllers\\RecoveryCodeController@store',
        'namespace' => 'Laravel\\Fortify\\Http\\Controllers',
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'two-factor.regenerate-recovery-codes',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'sanctum.csrf-cookie' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'sanctum/csrf-cookie',
      'action' => 
      array (
        'uses' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'controller' => 'Laravel\\Sanctum\\Http\\Controllers\\CsrfCookieController@show',
        'namespace' => NULL,
        'prefix' => 'sanctum',
        'where' => 
        array (
        ),
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'sanctum.csrf-cookie',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4hZCG3eHNANlCp25' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'flux/flux.js',
      'action' => 
      array (
        'uses' => 'Flux\\AssetManager@fluxJs',
        'controller' => 'Flux\\AssetManager@fluxJs',
        'as' => 'generated::4hZCG3eHNANlCp25',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IjuRDKxEFyQnlPvl' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'flux/flux.min.js',
      'action' => 
      array (
        'uses' => 'Flux\\AssetManager@fluxMinJs',
        'controller' => 'Flux\\AssetManager@fluxMinJs',
        'as' => 'generated::IjuRDKxEFyQnlPvl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5ZSd8wOMkfJb1QJv' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'flux/editor.css',
      'action' => 
      array (
        'uses' => 'Flux\\AssetManager@editorCss',
        'controller' => 'Flux\\AssetManager@editorCss',
        'as' => 'generated::5ZSd8wOMkfJb1QJv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::NMWjlBireFPQ6ypn' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'flux/editor.js',
      'action' => 
      array (
        'uses' => 'Flux\\AssetManager@editorJs',
        'controller' => 'Flux\\AssetManager@editorJs',
        'as' => 'generated::NMWjlBireFPQ6ypn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::U2pd1ooeiHPPmuay' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'flux/editor.min.js',
      'action' => 
      array (
        'uses' => 'Flux\\AssetManager@editorMinJs',
        'controller' => 'Flux\\AssetManager@editorMinJs',
        'as' => 'generated::U2pd1ooeiHPPmuay',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0oUHp4awVti1VRwk' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'livewire/livewire.js',
      'action' => 
      array (
        'uses' => 'Livewire\\Mechanisms\\FrontendAssets\\FrontendAssets@returnJavaScriptAsFile',
        'controller' => 'Livewire\\Mechanisms\\FrontendAssets\\FrontendAssets@returnJavaScriptAsFile',
        'as' => 'generated::0oUHp4awVti1VRwk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LBKwt1H3my40qBxc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'livewire/livewire.min.js.map',
      'action' => 
      array (
        'uses' => 'Livewire\\Mechanisms\\FrontendAssets\\FrontendAssets@maps',
        'controller' => 'Livewire\\Mechanisms\\FrontendAssets\\FrontendAssets@maps',
        'as' => 'generated::LBKwt1H3my40qBxc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'livewire.upload-file' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'livewire/upload-file',
      'action' => 
      array (
        'uses' => 'Livewire\\Features\\SupportFileUploads\\FileUploadController@handle',
        'controller' => 'Livewire\\Features\\SupportFileUploads\\FileUploadController@handle',
        'as' => 'livewire.upload-file',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'livewire.preview-file' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'livewire/preview-file/{filename}',
      'action' => 
      array (
        'uses' => 'Livewire\\Features\\SupportFileUploads\\FilePreviewController@handle',
        'controller' => 'Livewire\\Features\\SupportFileUploads\\FilePreviewController@handle',
        'as' => 'livewire.preview-file',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zV2XcdYRDNziABZL' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/register',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@register',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@register',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::zV2XcdYRDNziABZL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::y15g35ObL4DM2MQZ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@login',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@login',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::y15g35ObL4DM2MQZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RE1Lv90aoWdSjYYB' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/tattooers',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerController@index',
        'namespace' => NULL,
        'prefix' => 'api/tattooers',
        'where' => 
        array (
        ),
        'as' => 'generated::RE1Lv90aoWdSjYYB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::y1xJiISsOWd4pni9' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/tattooers/{id}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerController@show',
        'namespace' => NULL,
        'prefix' => 'api/tattooers',
        'where' => 
        array (
        ),
        'as' => 'generated::y1xJiISsOWd4pni9',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4BqND8pklhoUBtrf' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/tattooers/{id}/portfolio',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerController@portfolio',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerController@portfolio',
        'namespace' => NULL,
        'prefix' => 'api/tattooers',
        'where' => 
        array (
        ),
        'as' => 'generated::4BqND8pklhoUBtrf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FhCbHp6ThJ0hK1YC' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/tattooers/{id}/availability',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerController@availability',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerController@availability',
        'namespace' => NULL,
        'prefix' => 'api/tattooers',
        'where' => 
        array (
        ),
        'as' => 'generated::FhCbHp6ThJ0hK1YC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CP8nlRbSQITC5IKC' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/marketplace/search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MarketplaceController@search',
        'controller' => 'App\\Http\\Controllers\\Api\\MarketplaceController@search',
        'namespace' => NULL,
        'prefix' => 'api/marketplace',
        'where' => 
        array (
        ),
        'as' => 'generated::CP8nlRbSQITC5IKC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::om93qw6bZvShyuEv' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/marketplace/suggestions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MarketplaceController@suggestions',
        'controller' => 'App\\Http\\Controllers\\Api\\MarketplaceController@suggestions',
        'namespace' => NULL,
        'prefix' => 'api/marketplace',
        'where' => 
        array (
        ),
        'as' => 'generated::om93qw6bZvShyuEv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::hlagrdE8oHEYLzoG' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/marketplace/featured',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MarketplaceController@featured',
        'controller' => 'App\\Http\\Controllers\\Api\\MarketplaceController@featured',
        'namespace' => NULL,
        'prefix' => 'api/marketplace',
        'where' => 
        array (
        ),
        'as' => 'generated::hlagrdE8oHEYLzoG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Y6tTDIovoLHhI2AB' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/marketplace/filters',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MarketplaceController@filters',
        'controller' => 'App\\Http\\Controllers\\Api\\MarketplaceController@filters',
        'namespace' => NULL,
        'prefix' => 'api/marketplace',
        'where' => 
        array (
        ),
        'as' => 'generated::Y6tTDIovoLHhI2AB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zrqTSMTYG5p129fI' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/marketplace/stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MarketplaceController@stats',
        'controller' => 'App\\Http\\Controllers\\Api\\MarketplaceController@stats',
        'namespace' => NULL,
        'prefix' => 'api/marketplace',
        'where' => 
        array (
        ),
        'as' => 'generated::zrqTSMTYG5p129fI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::VzV4ap1f0OMWwWFW' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/user',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:115:"function (\\Illuminate\\Http\\Request $request) {
        return $request->user()->load([\'client\', \'tattooer\']);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"0000000000000fb10000000000000000";}}',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::VzV4ap1f0OMWwWFW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8aticEo8h7vCZmI4' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/logout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AuthController@logout',
        'controller' => 'App\\Http\\Controllers\\Api\\AuthController@logout',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::8aticEo8h7vCZmI4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KddILVfwH8av9Sxg' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/fcm-token',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\FCMController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\FCMController@store',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::KddILVfwH8av9Sxg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Dlo9oGZVg9aoBTNp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/conversations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ConversationController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\ConversationController@index',
        'namespace' => NULL,
        'prefix' => 'api/conversations',
        'where' => 
        array (
        ),
        'as' => 'generated::Dlo9oGZVg9aoBTNp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::of65P6S6y1UVLrfh' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/conversations/archived',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ConversationController@archived',
        'controller' => 'App\\Http\\Controllers\\Api\\ConversationController@archived',
        'namespace' => NULL,
        'prefix' => 'api/conversations',
        'where' => 
        array (
        ),
        'as' => 'generated::of65P6S6y1UVLrfh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::hJcPG2lxH4Y8t34s' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/conversations/{conversation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ConversationController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\ConversationController@show',
        'namespace' => NULL,
        'prefix' => 'api/conversations',
        'where' => 
        array (
        ),
        'as' => 'generated::hJcPG2lxH4Y8t34s',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ca0WnA0tVowHa7rc' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/conversations/{conversation}/mark-as-read',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ConversationController@markAsRead',
        'controller' => 'App\\Http\\Controllers\\Api\\ConversationController@markAsRead',
        'namespace' => NULL,
        'prefix' => 'api/conversations',
        'where' => 
        array (
        ),
        'as' => 'generated::ca0WnA0tVowHa7rc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Fb5rYZkkaDZdC7TX' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/conversations/{conversation}/toggle-mute',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ConversationController@toggleMute',
        'controller' => 'App\\Http\\Controllers\\Api\\ConversationController@toggleMute',
        'namespace' => NULL,
        'prefix' => 'api/conversations',
        'where' => 
        array (
        ),
        'as' => 'generated::Fb5rYZkkaDZdC7TX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::B7PLZTL8d7QxV1Uc' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/conversations/{conversation}/archive',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ConversationController@archive',
        'controller' => 'App\\Http\\Controllers\\Api\\ConversationController@archive',
        'namespace' => NULL,
        'prefix' => 'api/conversations',
        'where' => 
        array (
        ),
        'as' => 'generated::B7PLZTL8d7QxV1Uc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::falC1s86XJ9j9YbX' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/conversations/{conversation}/block',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ConversationController@block',
        'controller' => 'App\\Http\\Controllers\\Api\\ConversationController@block',
        'namespace' => NULL,
        'prefix' => 'api/conversations',
        'where' => 
        array (
        ),
        'as' => 'generated::falC1s86XJ9j9YbX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZHNm3zJeIyuK0lpu' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/conversations/{conversation}/messages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MessageController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\MessageController@index',
        'namespace' => NULL,
        'prefix' => 'api/conversations',
        'where' => 
        array (
        ),
        'as' => 'generated::ZHNm3zJeIyuK0lpu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ilSZ3pAWs8Bx70Vo' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/conversations/{conversation}/messages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'throttle:messages',
          3 => 'secure.file.upload',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MessageController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\MessageController@store',
        'namespace' => NULL,
        'prefix' => 'api/conversations',
        'where' => 
        array (
        ),
        'as' => 'generated::ilSZ3pAWs8Bx70Vo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Ki4mSvbq1TgMrCd9' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/conversations/{conversation}/messages/{message}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MessageController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\MessageController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/conversations',
        'where' => 
        array (
        ),
        'as' => 'generated::Ki4mSvbq1TgMrCd9',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wNbHu7tSU7htrJtU' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/conversations/{conversation}/design-versions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MessageController@designVersions',
        'controller' => 'App\\Http\\Controllers\\Api\\MessageController@designVersions',
        'namespace' => NULL,
        'prefix' => 'api/conversations',
        'where' => 
        array (
        ),
        'as' => 'generated::wNbHu7tSU7htrJtU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gPlzKgvfO5mpibPb' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/conversations/{conversation}/messages/search',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MessageController@search',
        'controller' => 'App\\Http\\Controllers\\Api\\MessageController@search',
        'namespace' => NULL,
        'prefix' => 'api/conversations',
        'where' => 
        array (
        ),
        'as' => 'generated::gPlzKgvfO5mpibPb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'messages.download' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/messages/{message}/download',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\MessageController@downloadAttachment',
        'controller' => 'App\\Http\\Controllers\\Api\\MessageController@downloadAttachment',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'messages.download',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::AOFfqdgj1JXXVsd5' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookings/{bookingRequest}/payment/deposit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
          2 => 'throttle:payments',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\PaymentController@createDepositPayment',
        'controller' => 'App\\Http\\Controllers\\Api\\PaymentController@createDepositPayment',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::AOFfqdgj1JXXVsd5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bYcXI3NP5B1T1634' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/tattooers/{tattooer}/portfolio',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerController@uploadPortfolioImage',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerController@uploadPortfolioImage',
        'namespace' => NULL,
        'prefix' => 'api/tattooers/{tattooer}',
        'where' => 
        array (
        ),
        'as' => 'generated::bYcXI3NP5B1T1634',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::xrfSV0JkiDnnGij6' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/tattooers/{tattooer}/portfolio/{mediaId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerController@deletePortfolioImage',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerController@deletePortfolioImage',
        'namespace' => NULL,
        'prefix' => 'api/tattooers/{tattooer}',
        'where' => 
        array (
        ),
        'as' => 'generated::xrfSV0JkiDnnGij6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zd6HdISEc2dtxxk0' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/tattooers/{tattooer}/working-hours',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerController@getWorkingHours',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerController@getWorkingHours',
        'namespace' => NULL,
        'prefix' => 'api/tattooers/{tattooer}',
        'where' => 
        array (
        ),
        'as' => 'generated::zd6HdISEc2dtxxk0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wYpWGAitJL2pa2Bd' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/tattooers/{tattooer}/working-hours',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerController@updateWorkingHours',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerController@updateWorkingHours',
        'namespace' => NULL,
        'prefix' => 'api/tattooers/{tattooer}',
        'where' => 
        array (
        ),
        'as' => 'generated::wYpWGAitJL2pa2Bd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::zJwY6aqloty2Xooy' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/tattooers/{tattooer}/working-hours/{day}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerController@updateDayWorkingHours',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerController@updateDayWorkingHours',
        'namespace' => NULL,
        'prefix' => 'api/tattooers/{tattooer}',
        'where' => 
        array (
        ),
        'as' => 'generated::zJwY6aqloty2Xooy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'day' => '[0-6]',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::lDrKe2R7TdKU2EzT' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/booking-requests',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@index',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::lDrKe2R7TdKU2EzT',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LxfEhhVc04BkojLp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/booking-requests/statistics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@statistics',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@statistics',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::LxfEhhVc04BkojLp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::M7HRDItwC9gTqXcU' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/booking-requests',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@store',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::M7HRDItwC9gTqXcU',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4XD9quApiq6IV65Z' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/booking-requests/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@show',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::4XD9quApiq6IV65Z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YkvUUQWCH6P5fpJa' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/booking-requests/{bookingRequest}/accept',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@accept',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@accept',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::YkvUUQWCH6P5fpJa',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qqhnmcvSoF5wtnBJ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/booking-requests/{bookingRequest}/reject',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@reject',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@reject',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::qqhnmcvSoF5wtnBJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uzN7r0YRO9ifFL6L' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/booking-requests/{bookingRequest}/confirm-deposit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@confirmDeposit',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@confirmDeposit',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::uzN7r0YRO9ifFL6L',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0a3C2PrzOi0l9Y0p' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/booking-requests/{bookingRequest}/send-design',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@sendDesign',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@sendDesign',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::0a3C2PrzOi0l9Y0p',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BmtqId8gJF3NguXw' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/booking-requests/check-expired',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@checkExpiredRequests',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@checkExpiredRequests',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::BmtqId8gJF3NguXw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::B7MxWvGJ6obfmN1Z' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/booking-requests/{bookingRequest}/confirm-appointment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@confirmAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@confirmAppointment',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::B7MxWvGJ6obfmN1Z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::D8mT8hqugHGcF6xo' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/booking-requests/{bookingRequest}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@cancel',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@cancel',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::D8mT8hqugHGcF6xo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5febar9bOFeQilbB' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@index',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::5febar9bOFeQilbB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::V8vpM4YkHF4Enc8E' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@store',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::V8vpM4YkHF4Enc8E',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0k72Bj7myGcEe6my' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/bookings/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@show',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::0k72Bj7myGcEe6my',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1FLDDn5CIlH3tbbJ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookings/{bookingRequest}/accept',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@accept',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@accept',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::1FLDDn5CIlH3tbbJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::hr5VzoEC82TySy97' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookings/{bookingRequest}/reject',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@reject',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@reject',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::hr5VzoEC82TySy97',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::O9T2AYdGN4EDByi8' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookings/{bookingRequest}/confirm-appointment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@confirmAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@confirmAppointment',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::O9T2AYdGN4EDByi8',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RmOsy8vCEn4BlhNX' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/bookings/{bookingRequest}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@cancel',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@cancel',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::RmOsy8vCEn4BlhNX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LAKDsSxAHZSUISgd' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/appointments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@index',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::LAKDsSxAHZSUISgd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::fWevUqodJ5qa1H57' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/appointments/upcoming',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@upcoming',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@upcoming',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::fWevUqodJ5qa1H57',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::8FnAJtGkpABVzHfS' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/appointments/past',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@past',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@past',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::8FnAJtGkpABVzHfS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CGQS1HdSvPdpuvf1' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/appointments/statistics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@statistics',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@statistics',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::CGQS1HdSvPdpuvf1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BWNRUZ1ciVeEYQA6' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/appointments/require-confirmation',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@requireConfirmation',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@requireConfirmation',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::BWNRUZ1ciVeEYQA6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::b1vMFRLn9syCo5kd' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/appointments/calendar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@calendar',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@calendar',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::b1vMFRLn9syCo5kd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gG4PD2voFGoHBUa9' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/appointments/{appointment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@show',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::gG4PD2voFGoHBUa9',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Bc2W01Lj7I41USfz' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/appointments/{appointment}/confirm-completion',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@confirmCompletion',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@confirmCompletion',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::Bc2W01Lj7I41USfz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::igvaqu7lMMc8rZiZ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/appointments/{appointment}/report-no-show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@reportNoShow',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@reportNoShow',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::igvaqu7lMMc8rZiZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MUnHMCkAKHqtxhzt' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/appointments/{appointment}/report-issue',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@reportIssue',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@reportIssue',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::MUnHMCkAKHqtxhzt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WdCxkBF94a3sfqUv' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/appointments/{appointment}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@cancel',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@cancel',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::WdCxkBF94a3sfqUv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZPfbHBnRPNvhKevn' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/appointments/{appointment}/dispute-refund',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@disputeRefund',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@disputeRefund',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::ZPfbHBnRPNvhKevn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::vx2mcNZ0Fqtysfpv' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/availabilities',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@index',
        'namespace' => NULL,
        'prefix' => 'api/availabilities',
        'where' => 
        array (
        ),
        'as' => 'generated::vx2mcNZ0Fqtysfpv',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aS6ulyQp9SOs2yNz' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/availabilities',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@store',
        'namespace' => NULL,
        'prefix' => 'api/availabilities',
        'where' => 
        array (
        ),
        'as' => 'generated::aS6ulyQp9SOs2yNz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::51J0K9uREPxP0QpA' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/availabilities/{availability}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@update',
        'namespace' => NULL,
        'prefix' => 'api/availabilities',
        'where' => 
        array (
        ),
        'as' => 'generated::51J0K9uREPxP0QpA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LL3tyA6WjPsNiZky' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/availabilities/{availability}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/availabilities',
        'where' => 
        array (
        ),
        'as' => 'generated::LL3tyA6WjPsNiZky',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KpD3CDjaUmlFwnQd' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/availabilities/generate-from-working-hours',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@generateFromWorkingHours',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@generateFromWorkingHours',
        'namespace' => NULL,
        'prefix' => 'api/availabilities',
        'where' => 
        array (
        ),
        'as' => 'generated::KpD3CDjaUmlFwnQd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5CS4PdOkuwuaQF1n' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/availabilities/check',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@checkAvailability',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@checkAvailability',
        'namespace' => NULL,
        'prefix' => 'api/availabilities',
        'where' => 
        array (
        ),
        'as' => 'generated::5CS4PdOkuwuaQF1n',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qjw0ioMAPuBj07V5' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/planning/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@dashboard',
        'namespace' => NULL,
        'prefix' => 'api/planning',
        'where' => 
        array (
        ),
        'as' => 'generated::qjw0ioMAPuBj07V5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RxILwxvxN29IzORg' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/planning/block-slot',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@blockSlot',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@blockSlot',
        'namespace' => NULL,
        'prefix' => 'api/planning',
        'where' => 
        array (
        ),
        'as' => 'generated::RxILwxvxN29IzORg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::t8SpZR3oSiBPUaFn' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/planning/create-external-appointment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@createExternalAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@createExternalAppointment',
        'namespace' => NULL,
        'prefix' => 'api/planning',
        'where' => 
        array (
        ),
        'as' => 'generated::t8SpZR3oSiBPUaFn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YXlc0kEVJBXUUhyD' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'api/planning/release-slot/{availability}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@releaseSlot',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@releaseSlot',
        'namespace' => NULL,
        'prefix' => 'api/planning',
        'where' => 
        array (
        ),
        'as' => 'generated::YXlc0kEVJBXUUhyD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KcyrDvioduexUkVc' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/planning/tattooers/{tattooerId}/available-dates',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@availableDates',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@availableDates',
        'namespace' => NULL,
        'prefix' => 'api/planning',
        'where' => 
        array (
        ),
        'as' => 'generated::KcyrDvioduexUkVc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::NfE2EZFxGcXETnbg' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/planning/tattooers/{tattooerId}/slots-for-date',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@slotsForDate',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@slotsForDate',
        'namespace' => NULL,
        'prefix' => 'api/planning',
        'where' => 
        array (
        ),
        'as' => 'generated::NfE2EZFxGcXETnbg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::i9GkLzeMuGv1eVUz' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/care-sheets',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@index',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::i9GkLzeMuGv1eVUz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::eqhH7ZHmgXrjY2hk' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/care-sheets',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@store',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::eqhH7ZHmgXrjY2hk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ITyiAwfHZjRZlEfX' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/care-sheets/my-sheets',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@clientIndex',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@clientIndex',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::ITyiAwfHZjRZlEfX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WWeBX4CaNSzI74xx' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/care-sheets/statistics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@statistics',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@statistics',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::WWeBX4CaNSzI74xx',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iZwBYb4LAbg6JEIO' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/care-sheets/{careSheet}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@show',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::iZwBYb4LAbg6JEIO',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0RN3NpVMqGkBQwz5' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/care-sheets/{careSheet}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@update',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::0RN3NpVMqGkBQwz5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2ZGG8ucebnUQB1Mu' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/care-sheets/{careSheet}/photos',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@addPhoto',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@addPhoto',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::2ZGG8ucebnUQB1Mu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::RZXS1x40gcHQFoPC' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/care-sheets/appointments/{appointment}/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@createFromAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@createFromAppointment',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::RZXS1x40gcHQFoPC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Qsa3BOvGApVYQTI4' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/inventory',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@index',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::Qsa3BOvGApVYQTI4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1KCO6AormAY6oDoP' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/inventory',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@store',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::1KCO6AormAY6oDoP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YUZtQTI2SKXtQSFM' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/inventory/statistics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@statistics',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@statistics',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::YUZtQTI2SKXtQSFM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HfuonHukgk5MLSFD' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/inventory/alerts',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@alerts',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@alerts',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::HfuonHukgk5MLSFD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::U7ukjtXQjEKEvZB5' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/inventory/{item}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@show',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::U7ukjtXQjEKEvZB5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::19Jj30uKFvmswZhm' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'api/inventory/{item}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@update',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::19Jj30uKFvmswZhm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::GhqfytunDFFfi48T' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/inventory/{item}/movement',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@movement',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@movement',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::GhqfytunDFFfi48T',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::vi6rw5mE1Y19mixu' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/inventory/{item}/adjust',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@adjustStock',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@adjustStock',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::vi6rw5mE1Y19mixu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bPChVjO3OxAJPnnE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/inventory/{item}/movements',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@movements',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@movements',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::bPChVjO3OxAJPnnE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5GsviV5RcLIIoV4m' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/accounting/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@dashboard',
        'namespace' => NULL,
        'prefix' => 'api/accounting',
        'where' => 
        array (
        ),
        'as' => 'generated::5GsviV5RcLIIoV4m',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cwqdcum1f4p2cmIp' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/accounting/report',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@report',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@report',
        'namespace' => NULL,
        'prefix' => 'api/accounting',
        'where' => 
        array (
        ),
        'as' => 'generated::cwqdcum1f4p2cmIp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KDigcj8V8fZA9cBy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/accounting/appointment-stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@appointmentStats',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@appointmentStats',
        'namespace' => NULL,
        'prefix' => 'api/accounting',
        'where' => 
        array (
        ),
        'as' => 'generated::KDigcj8V8fZA9cBy',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EaVD3C74XtyWcofg' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/accounting/export',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@export',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@export',
        'namespace' => NULL,
        'prefix' => 'api/accounting',
        'where' => 
        array (
        ),
        'as' => 'generated::EaVD3C74XtyWcofg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FVyUKTC5jK9z0ZKX' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/accounting/transactions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@transactions',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@transactions',
        'namespace' => NULL,
        'prefix' => 'api/accounting/transactions',
        'where' => 
        array (
        ),
        'as' => 'generated::FVyUKTC5jK9z0ZKX',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::eFSMwUGsE9Q5ASvj' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/accounting/transactions',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@storeTransaction',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@storeTransaction',
        'namespace' => NULL,
        'prefix' => 'api/accounting/transactions',
        'where' => 
        array (
        ),
        'as' => 'generated::eFSMwUGsE9Q5ASvj',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5MRDu3aDOD3D2bsG' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/accounting/transactions/{transaction}/mark-paid',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@markAsPaid',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@markAsPaid',
        'namespace' => NULL,
        'prefix' => 'api/accounting/transactions',
        'where' => 
        array (
        ),
        'as' => 'generated::5MRDu3aDOD3D2bsG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cLa8OFBrSbmXLeL8' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/messages/unread-count',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UnreadMessagesController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\UnreadMessagesController@index',
        'namespace' => NULL,
        'prefix' => 'api/messages',
        'where' => 
        array (
        ),
        'as' => 'generated::cLa8OFBrSbmXLeL8',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::voyKGrdyGKX2SoJV' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/traceability/consent-forms',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@consentForms',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@consentForms',
        'namespace' => NULL,
        'prefix' => 'api/traceability/consent-forms',
        'where' => 
        array (
        ),
        'as' => 'generated::voyKGrdyGKX2SoJV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Q6OAbCBPjSy9udDQ' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/traceability/consent-forms',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@storeConsentForm',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@storeConsentForm',
        'namespace' => NULL,
        'prefix' => 'api/traceability/consent-forms',
        'where' => 
        array (
        ),
        'as' => 'generated::Q6OAbCBPjSy9udDQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wcfLzGLqIXC8HAbo' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/traceability/consent-forms/{consentForm}/verify',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@verifyConsentForm',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@verifyConsentForm',
        'namespace' => NULL,
        'prefix' => 'api/traceability/consent-forms',
        'where' => 
        array (
        ),
        'as' => 'generated::wcfLzGLqIXC8HAbo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::fX57re7FZlM7P5Av' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/traceability/consent-forms/{consentForm}/parental-consent',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@storeParentalConsent',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@storeParentalConsent',
        'namespace' => NULL,
        'prefix' => 'api/traceability/consent-forms',
        'where' => 
        array (
        ),
        'as' => 'generated::fX57re7FZlM7P5Av',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::G8dTsAjBhVNsiomL' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/traceability/records',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@traceabilityRecords',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@traceabilityRecords',
        'namespace' => NULL,
        'prefix' => 'api/traceability/records',
        'where' => 
        array (
        ),
        'as' => 'generated::G8dTsAjBhVNsiomL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mw4eyxMrtPDokD0R' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/traceability/records',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@storeTraceabilityRecord',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@storeTraceabilityRecord',
        'namespace' => NULL,
        'prefix' => 'api/traceability/records',
        'where' => 
        array (
        ),
        'as' => 'generated::mw4eyxMrtPDokD0R',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::g0R6ViaTjZX8qtSx' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/traceability/records/{record}/materials',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@addMaterials',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@addMaterials',
        'namespace' => NULL,
        'prefix' => 'api/traceability/records',
        'where' => 
        array (
        ),
        'as' => 'generated::g0R6ViaTjZX8qtSx',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cGeTt0su5igPs7vd' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/traceability/records/{record}/photos',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@addPhotos',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@addPhotos',
        'namespace' => NULL,
        'prefix' => 'api/traceability/records',
        'where' => 
        array (
        ),
        'as' => 'generated::cGeTt0su5igPs7vd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rn6Ey339QGcWaDYI' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/traceability/records/{record}/verify',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@verifyTraceability',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@verifyTraceability',
        'namespace' => NULL,
        'prefix' => 'api/traceability/records',
        'where' => 
        array (
        ),
        'as' => 'generated::rn6Ey339QGcWaDYI',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::OAO11TxaHbbouAyK' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/traceability/records/{record}/report',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@generateReport',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@generateReport',
        'namespace' => NULL,
        'prefix' => 'api/traceability/records',
        'where' => 
        array (
        ),
        'as' => 'generated::OAO11TxaHbbouAyK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cB4avp1XpHne4hkQ' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'api/traceability/statistics',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
          1 => 'auth:sanctum',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@statistics',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@statistics',
        'namespace' => NULL,
        'prefix' => 'api/traceability',
        'where' => 
        array (
        ),
        'as' => 'generated::cB4avp1XpHne4hkQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::iRneuOILvGo76Ab8' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'up',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:833:"function () {
                    $exception = null;

                    try {
                        \\Illuminate\\Support\\Facades\\Event::dispatch(new \\Illuminate\\Foundation\\Events\\DiagnosingHealth);
                    } catch (\\Throwable $e) {
                        if (app()->hasDebugModeEnabled()) {
                            throw $e;
                        }

                        report($e);

                        $exception = $e->getMessage();
                    }

                    return response(\\Illuminate\\Support\\Facades\\View::file(\'C:\\\\laragon\\\\www\\\\tattoolib-saas\\\\vendor\\\\laravel\\\\framework\\\\src\\\\Illuminate\\\\Foundation\\\\Configuration\'.\'/../resources/health-up.blade.php\', [
                        \'exception\' => $exception,
                    ]), status: $exception ? 500 : 200);
                }";s:5:"scope";s:54:"Illuminate\\Foundation\\Configuration\\ApplicationBuilder";s:4:"this";N;s:4:"self";s:32:"0000000000000fa60000000000000000";}}',
        'as' => 'generated::iRneuOILvGo76Ab8',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'home' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => '/',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:185:"function () {
    $cacheService = \\app(\\App\\Services\\CacheService::class);
    $artists = $cacheService->getMarketplaceListings([]);

    return \\view(\'welcome\', \\compact(\'artists\'));
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"0000000000000fc50000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'home',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'professionnels.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'professionnels',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:57:"function () {
    return \\view(\'professionnels.index\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011e10000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'professionnels.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pricing' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tarifs',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:44:"function () {
    return \\view(\'pricing\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011df0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'pricing',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'marketplace.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'marketplace',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\MarketplaceController@index',
        'controller' => 'App\\Http\\Controllers\\MarketplaceController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'marketplace.index',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/profil',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerDashboardController@profile',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerDashboardController@profile',
        'as' => 'tattooer.profile',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerDashboardController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerDashboardController@dashboard',
        'as' => 'tattooer.dashboard',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.requests' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/requests',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@requests',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@requests',
        'as' => 'tattooer.requests',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.request.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/requests/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@requestShow',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@requestShow',
        'as' => 'tattooer.request.show',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.pricing' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/pricing',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerDashboardController@pricing',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerDashboardController@pricing',
        'as' => 'tattooer.pricing',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.request.accept.get' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/requests/{bookingRequest}/accept',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:227:"function (\\App\\Models\\BookingRequest $bookingRequest) {
        return \\redirect()->route(\'tattooer.request.show\', $bookingRequest)
            ->with(\'info\', \'Veuillez utiliser la modale d\\\'acceptation sur cette page.\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011d50000000000000000";}}',
        'as' => 'tattooer.request.accept.get',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.request.accept' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/requests/{bookingRequest}/accept',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@acceptRequest',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@acceptRequest',
        'as' => 'tattooer.request.accept',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.request-reject' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/requests/{bookingRequest}/reject',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@requestReject',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@requestReject',
        'as' => 'tattooer.request-reject',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.requests.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'tattooer/requests/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@destroyRequest',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@destroyRequest',
        'as' => 'tattooer.requests.destroy',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.requests.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'tattooer/requests/{bookingRequest}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@cancelRequest',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@cancelRequest',
        'as' => 'tattooer.requests.cancel',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.booking-requests.repropose-dates' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/booking-requests/{bookingRequest}/repropose-dates',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@reproposeDates',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@reproposeDates',
        'as' => 'tattooer.booking-requests.repropose-dates',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.calendar' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/calendar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendar',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendar',
        'as' => 'tattooer.calendar',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.calendar.events' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/calendar/events',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarEvents',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarEvents',
        'as' => 'tattooer.calendar.events',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.calendar.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/calendar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarStore',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarStore',
        'as' => 'tattooer.calendar.store',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.calendar.update' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'tattooer/calendar/{event}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarUpdate',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarUpdate',
        'as' => 'tattooer.calendar.update',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.calendar.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'tattooer/calendar/{event}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarDestroy',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarDestroy',
        'as' => 'tattooer.calendar.destroy',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.messages' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/messages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMessageController@messages',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMessageController@messages',
        'as' => 'tattooer.messages',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.message.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/messages/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMessageController@messageShow',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMessageController@messageShow',
        'as' => 'tattooer.message.show',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.message.send' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/message/{bookingRequest}/send',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMessageController@messageSend',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMessageController@messageSend',
        'as' => 'tattooer.message.send',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.booking-requests.complete' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/booking-requests/{bookingRequest}/complete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@completeBooking',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@completeBooking',
        'as' => 'tattooer.booking-requests.complete',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.booking-requests.no-show' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/booking-requests/{bookingRequest}/no-show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@markNoShow',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@markNoShow',
        'as' => 'tattooer.booking-requests.no-show',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.clients' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/clients',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@clients',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@clients',
        'as' => 'tattooer.clients',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.clients.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/clients/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@createClient',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@createClient',
        'as' => 'tattooer.clients.create',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.clients.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/clients',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@storeClient',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@storeClient',
        'as' => 'tattooer.clients.store',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.client.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/clients/{client}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@clientShow',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@clientShow',
        'as' => 'tattooer.client.show',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.clients.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'tattooer/clients/{client}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@updateClient',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@updateClient',
        'as' => 'tattooer.clients.update',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.clients.consent.upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/clients/{client}/consent/upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@uploadConsent',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@uploadConsent',
        'as' => 'tattooer.clients.consent.upload',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.clients.consent.store-digital' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/clients/{client}/consent/store-digital',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@storeDigitalConsent',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@storeDigitalConsent',
        'as' => 'tattooer.clients.consent.store-digital',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.clients.consent.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'tattooer/clients/{client}/consent/{media}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@deleteConsent',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@deleteConsent',
        'as' => 'tattooer.clients.consent.delete',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.clients.traceability.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/clients/{client}/traceability',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerTraceabilityController@storeClientTraceability',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerTraceabilityController@storeClientTraceability',
        'as' => 'tattooer.clients.traceability.store',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.clients.photos.upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/clients/{client}/photos/upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@uploadClientPhotos',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@uploadClientPhotos',
        'as' => 'tattooer.clients.photos.upload',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.clients.photos.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'tattooer/clients/{client}/photos/{media}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteClientPhoto',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteClientPhoto',
        'as' => 'tattooer.clients.photos.delete',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.client.update-notes' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/clients/{client}/notes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@updateClientNotes',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@updateClientNotes',
        'as' => 'tattooer.client.update-notes',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.consent.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/consent/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@storeConsent',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@storeConsent',
        'as' => 'tattooer.consent.store',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.traceability.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/traceability/{appointment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerTraceabilityController@storeTraceability',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerTraceabilityController@storeTraceability',
        'as' => 'tattooer.traceability.store',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.appointments.complete' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/appointments/{appointment}/complete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerAppointmentController@completeAppointment',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerAppointmentController@completeAppointment',
        'as' => 'tattooer.appointments.complete',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.appointments.no-show' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/appointments/{appointment}/no-show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerAppointmentController@reportNoShow',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerAppointmentController@reportNoShow',
        'as' => 'tattooer.appointments.no-show',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.balance-payment.confirm-offline' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/bookings/{bookingRequest}/balance/confirm-offline',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\BalancePaymentController@confirmOffline',
        'controller' => 'App\\Http\\Controllers\\BalancePaymentController@confirmOffline',
        'as' => 'tattooer.balance-payment.confirm-offline',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.client.photos.upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/client/{client}/photos/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@uploadClientTattooPhotos',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@uploadClientTattooPhotos',
        'as' => 'tattooer.client.photos.upload',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.client.media.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'tattooer/client/{client}/media/{media}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteClientMedia',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteClientMedia',
        'as' => 'tattooer.client.media.delete',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.client-requests' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/clients/{clientId}/requests',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@clientRequests',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@clientRequests',
        'as' => 'tattooer.client-requests',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.portfolio' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/portfolio',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolio',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolio',
        'as' => 'tattooer.portfolio',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.portfolio.upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/portfolio/upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolioUpload',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolioUpload',
        'as' => 'tattooer.portfolio.upload',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.portfolio.before-after.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/portfolio/before-after/store',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolioBeforeAfterStore',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolioBeforeAfterStore',
        'as' => 'tattooer.portfolio.before-after.store',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.portfolio.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'tattooer/portfolio/{media}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolioDestroy',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolioDestroy',
        'as' => 'tattooer.portfolio.destroy',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.portfolio.before-after.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'tattooer/portfolio/before-after/{beforeId}/{afterId}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolioBeforeAfterDestroy',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolioBeforeAfterDestroy',
        'as' => 'tattooer.portfolio.before-after.destroy',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.settings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settings',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settings',
        'as' => 'tattooer.settings',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.settings.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsUpdate',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsUpdate',
        'as' => 'tattooer.settings.update',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.settings.aftercare' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/settings/aftercare',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsAftercareUpdate',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsAftercareUpdate',
        'as' => 'tattooer.settings.aftercare',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.settings.pricing' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/settings/pricing',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsPricingUpdate',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsPricingUpdate',
        'as' => 'tattooer.settings.pricing',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.settings.delete-avatar' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'tattooer/settings/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteAvatar',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteAvatar',
        'as' => 'tattooer.settings.delete-avatar',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.settings.delete-banner' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'tattooer/settings/banner',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteBanner',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteBanner',
        'as' => 'tattooer.settings.delete-banner',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.settings.update-schedule' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/settings/schedule',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsUpdateSchedule',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsUpdateSchedule',
        'as' => 'tattooer.settings.update-schedule',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.settings.update-password' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/settings/password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsUpdatePassword',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsUpdatePassword',
        'as' => 'tattooer.settings.update-password',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.settings.hours.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/settings/hours',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@updateHours',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@updateHours',
        'as' => 'tattooer.settings.hours.update',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.gdpr.export' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/settings/export-gdpr',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
          3 => 'throttle:3,60',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@exportGdpr',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@exportGdpr',
        'as' => 'tattooer.gdpr.export',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.payments' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerPaymentController@payments',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerPaymentController@payments',
        'as' => 'tattooer.payments',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.stripe.connect' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/stripe/connect',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerPaymentController@connectStripe',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerPaymentController@connectStripe',
        'as' => 'tattooer.stripe.connect',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.subscription.plans' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/subscription-plans',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@plans',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@plans',
        'as' => 'tattooer.subscription.plans',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.subscription.subscribe' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/subscribe',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@subscribe',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@subscribe',
        'as' => 'tattooer.subscription.subscribe',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.subscription.success' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/subscription/success',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@success',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@success',
        'as' => 'tattooer.subscription.success',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.subscription.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/subscription/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@cancel',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@cancel',
        'as' => 'tattooer.subscription.cancel',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.subscription.resume' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/subscription/resume',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@resume',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@resume',
        'as' => 'tattooer.subscription.resume',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.subscription.manage' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/subscription/manage',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@manage',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@manage',
        'as' => 'tattooer.subscription.manage',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.compliance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/compliance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerComplianceController@compliance',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerComplianceController@compliance',
        'as' => 'tattooer.compliance',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.compliance.documents' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/compliance/documents',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerComplianceController@complianceDocuments',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerComplianceController@complianceDocuments',
        'as' => 'tattooer.compliance.documents',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.compliance.documents.upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'tattooer/compliance/documents',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerComplianceController@complianceDocumentsUpload',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerComplianceController@complianceDocumentsUpload',
        'as' => 'tattooer.compliance.documents.upload',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.compliance.documents.serve' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/compliance/documents/{complianceRecord}/view/{field}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerComplianceController@complianceDocumentServe',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerComplianceController@complianceDocumentServe',
        'as' => 'tattooer.compliance.documents.serve',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.compliance.documents.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'tattooer/compliance/documents/{complianceRecord}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerComplianceController@complianceDocumentDelete',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerComplianceController@complianceDocumentDelete',
        'as' => 'tattooer.compliance.documents.delete',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.profile.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/profil/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Profile@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Profile',
        'as' => 'tattooer.profile.edit',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.portfolio.livewire' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/portfolio-livewire',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Portfolio@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Portfolio',
        'as' => 'tattooer.portfolio.livewire',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.availability' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/disponibilites',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Availability@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Availability',
        'as' => 'tattooer.availability',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.demandes' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/demandes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\BookingRequests@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\BookingRequests',
        'as' => 'tattooer.demandes',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.messages.livewire' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/messages-livewire',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Messages@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Messages',
        'as' => 'tattooer.messages.livewire',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.bookings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/reservations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Bookings@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Bookings',
        'as' => 'tattooer.bookings',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.clients.livewire' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/clients-livewire',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Clients@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Clients',
        'as' => 'tattooer.clients.livewire',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.analytics' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/statistiques',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Analytics@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Analytics',
        'as' => 'tattooer.analytics',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.settings.livewire' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/parametres',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Settings@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Settings',
        'as' => 'tattooer.settings.livewire',
        'namespace' => NULL,
        'prefix' => '/tattooer',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.pending-verification' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/pending-verification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:86:"function () {
    return \\view(\'auth.pending-verification\', [\'role\' => \'tattooer\']);
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011dc0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'tattooer.pending-verification',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'marketplace.tattooer.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'tattooer/{slug}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\MarketplaceController@show',
        'controller' => 'App\\Http\\Controllers\\MarketplaceController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'marketplace.tattooer.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'marketplace.piercer.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'piercer/{slug}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\MarketplaceController@show',
        'controller' => 'App\\Http\\Controllers\\MarketplaceController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'marketplace.piercer.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register.plan' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'register/plan',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:196:"function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (\\auth()->check()) {
        return \\redirect()->route(\'home\');
    }
    return \\view(\'auth.register-plan\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011850000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register.plan',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register.client' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'register/client',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:208:"function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (\\auth()->check()) {
        return \\redirect()->route(\'client.profile\');
    }
    return \\view(\'auth.register-client\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011830000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register.client',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register.tattooer' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'register/tattooer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:214:"function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (\\auth()->check()) {
        return \\redirect()->route(\'tattooer.dashboard\');
    }
    return \\view(\'auth.register-tattooer\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011810000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register.tattooer',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register.pierceur' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'register/pierceur',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:136:"function () {
    if (\\auth()->check()) {
        return \\redirect()->route(\'home\');
    }
    return \\view(\'auth.register-pierceur\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000117f0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register.pierceur',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register.studio' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'register/studio',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:212:"function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (\\auth()->check()) {
        return \\redirect()->route(\'tattooer.dashboard\');
    }
    return \\view(\'auth.register-studio\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000117d0000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register.studio',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register.client.submit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'register/client',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:10,5',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:146:"function (\\Illuminate\\Http\\Request $request) {
        return \\app(\\App\\Http\\Controllers\\RegisterController::class)->submitClient($request);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011770000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register.client.submit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register.tattooer.submit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'register/tattooer',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:10,5',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:148:"function (\\Illuminate\\Http\\Request $request) {
        return \\app(\\App\\Http\\Controllers\\RegisterController::class)->submitTattooer($request);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011750000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register.tattooer.submit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register.pierceur.submit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'register/pierceur',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:10,5',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:147:"function (\\Illuminate\\Http\\Request $request) {
        return \\app(\\App\\Http\\Controllers\\RegisterController::class)->submitPiercer($request);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011730000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register.pierceur.submit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'register.studio.submit' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'register/studio',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:10,5',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:146:"function (\\Illuminate\\Http\\Request $request) {
        return \\app(\\App\\Http\\Controllers\\RegisterController::class)->submitStudio($request);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011710000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'register.studio.submit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientDashboardController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientDashboardController@dashboard',
        'as' => 'client.index',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientDashboardController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientDashboardController@dashboard',
        'as' => 'client.dashboard',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.booking-requests' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/booking-requests',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientBookingController@bookingRequests',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientBookingController@bookingRequests',
        'as' => 'client.booking-requests',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.booking-request.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/booking-requests/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientBookingController@bookingRequestShow',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientBookingController@bookingRequestShow',
        'as' => 'client.booking-request.show',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.booking-request.select-date' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'client/booking-requests/{bookingRequest}/select-date',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientBookingController@selectProposedDate',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientBookingController@selectProposedDate',
        'as' => 'client.booking-request.select-date',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.booking-request.request-alternatives' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'client/booking-requests/{bookingRequest}/request-alternatives',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientBookingController@requestAlternativeDates',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientBookingController@requestAlternativeDates',
        'as' => 'client.booking-request.request-alternatives',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.chat' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/chat/{conversation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientMessageController@chat',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientMessageController@chat',
        'as' => 'client.chat',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.message.send' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'client/message/{conversation}/send',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientMessageController@sendMessage',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientMessageController@sendMessage',
        'as' => 'client.message.send',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.reviews' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/reviews',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientSocialController@reviews',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientSocialController@reviews',
        'as' => 'client.reviews',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.complaints' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/complaints',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientSocialController@complaints',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientSocialController@complaints',
        'as' => 'client.complaints',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.complaints.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'client/complaints',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientSocialController@storeComplaint',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientSocialController@storeComplaint',
        'as' => 'client.complaints.store',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.reviews.create' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'client/reviews/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientSocialController@createReview',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientSocialController@createReview',
        'as' => 'client.reviews.create',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.complaints.create' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'client/complaints/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientSocialController@createComplaint',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientSocialController@createComplaint',
        'as' => 'client.complaints.create',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.booking-request.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'client/booking-requests/{bookingRequest}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientBookingController@bookingRequestCancel',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientBookingController@bookingRequestCancel',
        'as' => 'client.booking-request.cancel',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.requests.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'client/requests/{bookingRequest}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientBookingController@cancelRequest',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientBookingController@cancelRequest',
        'as' => 'client.requests.cancel',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.booking-request.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'client/booking-request/{bookingRequest}/delete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientBookingController@bookingRequestDelete',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientBookingController@bookingRequestDelete',
        'as' => 'client.booking-request.delete',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.balance.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/bookings/{bookingRequest}/balance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\BalancePaymentController@show',
        'controller' => 'App\\Http\\Controllers\\BalancePaymentController@show',
        'as' => 'client.balance.show',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.balance-payment.checkout' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'client/bookings/{bookingRequest}/balance/checkout',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\BalancePaymentController@checkout',
        'controller' => 'App\\Http\\Controllers\\BalancePaymentController@checkout',
        'as' => 'client.balance-payment.checkout',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.balance-payment.success' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/bookings/{bookingRequest}/balance/success',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\BalancePaymentController@success',
        'controller' => 'App\\Http\\Controllers\\BalancePaymentController@success',
        'as' => 'client.balance-payment.success',
        'namespace' => NULL,
        'prefix' => '/client',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/profil',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerDashboardController@profile',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerDashboardController@profile',
        'as' => 'pierceur.profile',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerDashboardController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerDashboardController@dashboard',
        'as' => 'pierceur.dashboard',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.requests' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/requests',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@requests',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@requests',
        'as' => 'pierceur.requests',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.request.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/requests/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@requestShow',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@requestShow',
        'as' => 'pierceur.request.show',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.request.accept.get' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/requests/{bookingRequest}/accept',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:227:"function (\\App\\Models\\BookingRequest $bookingRequest) {
        return \\redirect()->route(\'pierceur.request.show\', $bookingRequest)
            ->with(\'info\', \'Veuillez utiliser la modale d\\\'acceptation sur cette page.\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011580000000000000000";}}',
        'as' => 'pierceur.request.accept.get',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.request.accept' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/requests/{bookingRequest}/accept',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@acceptRequest',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@acceptRequest',
        'as' => 'pierceur.request.accept',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.request-reject' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/requests/{bookingRequest}/reject',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@requestReject',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@requestReject',
        'as' => 'pierceur.request-reject',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.requests.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pierceur/requests/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@destroyRequest',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@destroyRequest',
        'as' => 'pierceur.requests.destroy',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.requests.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'pierceur/requests/{bookingRequest}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@cancelRequest',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@cancelRequest',
        'as' => 'pierceur.requests.cancel',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.booking-requests.repropose-dates' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/booking-requests/{bookingRequest}/repropose-dates',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@reproposeDates',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@reproposeDates',
        'as' => 'pierceur.booking-requests.repropose-dates',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.calendar' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/calendar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendar',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendar',
        'as' => 'pierceur.calendar',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.calendar.events' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/calendar/events',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarEvents',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarEvents',
        'as' => 'pierceur.calendar.events',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.calendar.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/calendar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarStore',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarStore',
        'as' => 'pierceur.calendar.store',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.calendar.update' => 
    array (
      'methods' => 
      array (
        0 => 'PATCH',
      ),
      'uri' => 'pierceur/calendar/{event}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarUpdate',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarUpdate',
        'as' => 'pierceur.calendar.update',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.calendar.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pierceur/calendar/{event}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarDestroy',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerCalendarController@calendarDestroy',
        'as' => 'pierceur.calendar.destroy',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.messages' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/messages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMessageController@messages',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMessageController@messages',
        'as' => 'pierceur.messages',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.message.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/messages/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMessageController@messageShow',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMessageController@messageShow',
        'as' => 'pierceur.message.show',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.message.send' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/message/{bookingRequest}/send',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMessageController@messageSend',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMessageController@messageSend',
        'as' => 'pierceur.message.send',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.booking-requests.complete' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/booking-requests/{bookingRequest}/complete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@completeBooking',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@completeBooking',
        'as' => 'pierceur.booking-requests.complete',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.booking-requests.no-show' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/booking-requests/{bookingRequest}/no-show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@markNoShow',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerBookingController@markNoShow',
        'as' => 'pierceur.booking-requests.no-show',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.clients' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/clients',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@clients',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@clients',
        'as' => 'pierceur.clients',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.clients.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/clients/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
          4 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@createClient',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@createClient',
        'as' => 'pierceur.clients.create',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.clients.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/clients',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
          4 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@storeClient',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@storeClient',
        'as' => 'pierceur.clients.store',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.client.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/clients/{client}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@clientShow',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@clientShow',
        'as' => 'pierceur.client.show',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.clients.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'pierceur/clients/{client}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
          4 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@updateClient',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@updateClient',
        'as' => 'pierceur.clients.update',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.clients.consent.upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/clients/{client}/consent/upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
          4 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@uploadConsent',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@uploadConsent',
        'as' => 'pierceur.clients.consent.upload',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.clients.consent.store-digital' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/clients/{client}/consent/store-digital',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
          4 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@storeDigitalConsent',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@storeDigitalConsent',
        'as' => 'pierceur.clients.consent.store-digital',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.clients.consent.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pierceur/clients/{client}/consent/{media}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
          4 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@deleteConsent',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@deleteConsent',
        'as' => 'pierceur.clients.consent.delete',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.subscription.plans' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/subscription-plans',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@plans',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@plans',
        'as' => 'pierceur.subscription.plans',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.subscription.subscribe' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/subscribe',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@subscribe',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@subscribe',
        'as' => 'pierceur.subscription.subscribe',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.subscription.success' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/subscription/success',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@success',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@success',
        'as' => 'pierceur.subscription.success',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.subscription.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/subscription/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@cancel',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@cancel',
        'as' => 'pierceur.subscription.cancel',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.subscription.resume' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/subscription/resume',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@resume',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@resume',
        'as' => 'pierceur.subscription.resume',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.subscription.manage' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/subscription/manage',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@manage',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@manage',
        'as' => 'pierceur.subscription.manage',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.subscription.subscribeFromTrial' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/subscribe-from-trial',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\SubscriptionController@subscribeFromTrial',
        'controller' => 'App\\Http\\Controllers\\SubscriptionController@subscribeFromTrial',
        'as' => 'pierceur.subscription.subscribeFromTrial',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.clients.traceability.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/clients/{client}/traceability',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
          4 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerTraceabilityController@storeClientTraceability',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerTraceabilityController@storeClientTraceability',
        'as' => 'pierceur.clients.traceability.store',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.clients.photos.upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/clients/{client}/photos/upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
          4 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@uploadClientPhotos',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@uploadClientPhotos',
        'as' => 'pierceur.clients.photos.upload',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.clients.photos.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pierceur/clients/{client}/photos/{media}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
          4 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteClientPhoto',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteClientPhoto',
        'as' => 'pierceur.clients.photos.delete',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.client.update-notes' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/clients/{client}/notes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@updateClientNotes',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@updateClientNotes',
        'as' => 'pierceur.client.update-notes',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.client-requests' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/clients/{clientId}/requests',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@clientRequests',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerClientController@clientRequests',
        'as' => 'pierceur.client-requests',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.consent.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/consent/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@storeConsent',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerConsentController@storeConsent',
        'as' => 'pierceur.consent.store',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.traceability.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/traceability/{appointment}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerTraceabilityController@storeTraceability',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerTraceabilityController@storeTraceability',
        'as' => 'pierceur.traceability.store',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.appointments.complete' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/appointments/{appointment}/complete',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerAppointmentController@completeAppointment',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerAppointmentController@completeAppointment',
        'as' => 'pierceur.appointments.complete',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.appointments.no-show' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/appointments/{appointment}/no-show',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerAppointmentController@reportNoShow',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerAppointmentController@reportNoShow',
        'as' => 'pierceur.appointments.no-show',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.balance-payment.confirm-offline' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/bookings/{bookingRequest}/balance/confirm-offline',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\BalancePaymentController@confirmOffline',
        'controller' => 'App\\Http\\Controllers\\BalancePaymentController@confirmOffline',
        'as' => 'pierceur.balance-payment.confirm-offline',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.client.photos.upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/client/{client}/photos/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@uploadClientTattooPhotos',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@uploadClientTattooPhotos',
        'as' => 'pierceur.client.photos.upload',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.client.media.delete' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pierceur/client/{client}/media/{media}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteClientMedia',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteClientMedia',
        'as' => 'pierceur.client.media.delete',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.portfolio' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/portfolio',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolio',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolio',
        'as' => 'pierceur.portfolio',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.portfolio.upload' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/portfolio/upload',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolioUpload',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolioUpload',
        'as' => 'pierceur.portfolio.upload',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.portfolio.destroy' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pierceur/portfolio/{media}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolioDestroy',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerPortfolioController@portfolioDestroy',
        'as' => 'pierceur.portfolio.destroy',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.settings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settings',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settings',
        'as' => 'pierceur.settings',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.settings.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsUpdate',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsUpdate',
        'as' => 'pierceur.settings.update',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.settings.aftercare' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/settings/aftercare',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsAftercareUpdate',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsAftercareUpdate',
        'as' => 'pierceur.settings.aftercare',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.settings.pricing' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/settings/pricing',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsPricingUpdate',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsPricingUpdate',
        'as' => 'pierceur.settings.pricing',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.settings.delete-avatar' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pierceur/settings/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteAvatar',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteAvatar',
        'as' => 'pierceur.settings.delete-avatar',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.settings.delete-banner' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pierceur/settings/banner',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteBanner',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerMediaController@deleteBanner',
        'as' => 'pierceur.settings.delete-banner',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.settings.update-schedule' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/settings/schedule',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsUpdateSchedule',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsUpdateSchedule',
        'as' => 'pierceur.settings.update-schedule',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.settings.update-password' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/settings/password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsUpdatePassword',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@settingsUpdatePassword',
        'as' => 'pierceur.settings.update-password',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.settings.hours.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/settings/hours',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@updateHours',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@updateHours',
        'as' => 'pierceur.settings.hours.update',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.gdpr.export' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/settings/export-gdpr',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
          4 => 'throttle:3,60',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@exportGdpr',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerSettingsController@exportGdpr',
        'as' => 'pierceur.gdpr.export',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.payments' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/payments',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerPaymentController@payments',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerPaymentController@payments',
        'as' => 'pierceur.payments',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.stripe.connect' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'pierceur/stripe/connect',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerPaymentController@connectStripe',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerPaymentController@connectStripe',
        'as' => 'pierceur.stripe.connect',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.compliance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/compliance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\TattooerComplianceController@compliance',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\TattooerComplianceController@compliance',
        'as' => 'pierceur.compliance',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.profile.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/profil/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Profile@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Profile',
        'as' => 'pierceur.profile.edit',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.availability' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/disponibilites',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Availability@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Availability',
        'as' => 'pierceur.availability',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.demandes' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/demandes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\BookingRequests@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\BookingRequests',
        'as' => 'pierceur.demandes',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.bookings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/reservations',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Bookings@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Bookings',
        'as' => 'pierceur.bookings',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.analytics' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/statistiques',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Analytics@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Analytics',
        'as' => 'pierceur.analytics',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.messages.livewire' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/messages-livewire',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:pierceur,Piercer',
          3 => 'artisan.can.operate',
        ),
        'uses' => 'App\\Livewire\\Tattooer\\Messages@__invoke',
        'controller' => 'App\\Livewire\\Tattooer\\Messages',
        'as' => 'pierceur.messages.livewire',
        'namespace' => NULL,
        'prefix' => '/pierceur',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.public' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studios/{slug}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioDashboardController@publicProfile',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioDashboardController@publicProfile',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'studio.public',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.public.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'salon/{slug}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioDashboardController@publicProfile',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioDashboardController@publicProfile',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'studio.public.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.invitation.accept' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/invitation/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@acceptInvitation',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@acceptInvitation',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'studio.invitation.accept',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.invitation.process' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'studio/invitation/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@processInvitation',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@processInvitation',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'studio.invitation.process',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\Studio\\Dashboard@__invoke',
        'controller' => 'App\\Livewire\\Studio\\Dashboard',
        'as' => 'studio.dashboard',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/profil',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\Studio\\Profile@__invoke',
        'controller' => 'App\\Livewire\\Studio\\Profile',
        'as' => 'studio.profile',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.profile.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/profil/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\Studio\\Profile@__invoke',
        'controller' => 'App\\Livewire\\Studio\\Profile',
        'as' => 'studio.profile.edit',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.messages' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/messages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\Studio\\Messages@__invoke',
        'controller' => 'App\\Livewire\\Studio\\Messages',
        'as' => 'studio.messages',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.settings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/parametres',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\Studio\\Settings@__invoke',
        'controller' => 'App\\Livewire\\Studio\\Settings',
        'as' => 'studio.settings',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.settings.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'studio/parametres',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioSettingsController@updateSettings',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioSettingsController@updateSettings',
        'as' => 'studio.settings.update',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.calendar' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/calendar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\Studio\\Calendar@__invoke',
        'controller' => 'App\\Livewire\\Studio\\Calendar',
        'as' => 'studio.calendar',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.artists' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/artists',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@artists',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@artists',
        'as' => 'studio.artists',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.artists.create' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/artists/create',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@createArtist',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@createArtist',
        'as' => 'studio.artists.create',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.artists.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'studio/artists',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@storeArtist',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@storeArtist',
        'as' => 'studio.artists.store',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.artists.invite' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'studio/artists/invite',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@inviteArtist',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@inviteArtist',
        'as' => 'studio.artists.invite',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.artists.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/artists/{studioArtist}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@artistShow',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@artistShow',
        'as' => 'studio.artists.show',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.artists.remove' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'studio/artists/{studioArtist}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@removeArtist',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@removeArtist',
        'as' => 'studio.artists.remove',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.artists.toggle' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'studio/artists/{studioArtist}/toggle',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@toggleArtist',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@toggleArtist',
        'as' => 'studio.artists.toggle',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.planning' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/planning',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioDashboardController@planning',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioDashboardController@planning',
        'as' => 'studio.planning',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.planning.events' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/planning/events',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioDashboardController@planningEvents',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioDashboardController@planningEvents',
        'as' => 'studio.planning.events',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.requests' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/demandes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioBookingController@requests',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioBookingController@requests',
        'as' => 'studio.requests',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.demandes.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/demandes/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioBookingController@demandeShow',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioBookingController@demandeShow',
        'as' => 'studio.demandes.show',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.clients.index' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/clients',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@clients',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@clients',
        'as' => 'studio.clients.index',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.clients.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/clients/{client}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@clientShow',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@clientShow',
        'as' => 'studio.clients.show',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.clients.update' => 
    array (
      'methods' => 
      array (
        0 => 'PUT',
      ),
      'uri' => 'studio/clients/{client}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@clientUpdate',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioArtistController@clientUpdate',
        'as' => 'studio.clients.update',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.billing' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/billing',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@billing',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@billing',
        'as' => 'studio.billing',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.subscribe' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/subscribe',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@billing',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@billing',
        'as' => 'studio.subscribe',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.subscribe.post' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'studio/subscribe',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@subscribe',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@subscribe',
        'as' => 'studio.subscribe.post',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.subscription.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'studio/subscription/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@cancelSubscription',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@cancelSubscription',
        'as' => 'studio.subscription.cancel',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.subscription.resume' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'studio/subscription/resume',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@resumeSubscription',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@resumeSubscription',
        'as' => 'studio.subscription.resume',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.subscription.sync' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'studio/subscription/sync',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@syncSubscription',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@syncSubscription',
        'as' => 'studio.subscription.sync',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.subscribe.legacy' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/souscrire',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@showSubscribe',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@showSubscribe',
        'as' => 'studio.subscribe.legacy',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.subscribe.legacy.process' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'studio/souscrire',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@processSubscribe',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@processSubscribe',
        'as' => 'studio.subscribe.legacy.process',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.stats' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/stats',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioDashboardController@stats',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioDashboardController@stats',
        'as' => 'studio.stats',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.stripe.connect' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'studio/stripe/connect',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@connectStripe',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@connectStripe',
        'as' => 'studio.stripe.connect',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.upgrade' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/upgrade',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:65:"function () {
        return \\view(\'professionnels.index\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010f30000000000000000";}}',
        'as' => 'studio.upgrade',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.compliance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/compliance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'role:studio',
          3 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:62:"function () {
        return \\view(\'studio.compliance\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010f10000000000000000";}}',
        'as' => 'studio.compliance',
        'namespace' => NULL,
        'prefix' => '/studio',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio-artist.dashboard' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio-artist/dashboard',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\StudioArtist\\Dashboard@__invoke',
        'controller' => 'App\\Livewire\\StudioArtist\\Dashboard',
        'as' => 'studio-artist.dashboard',
        'namespace' => NULL,
        'prefix' => '/studio-artist',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio-artist.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio-artist/profil',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\StudioArtist\\Profile@__invoke',
        'controller' => 'App\\Livewire\\StudioArtist\\Profile',
        'as' => 'studio-artist.profile',
        'namespace' => NULL,
        'prefix' => '/studio-artist',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio-artist.profile.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio-artist/profil/edit',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\StudioArtist\\Profile@__invoke',
        'controller' => 'App\\Livewire\\StudioArtist\\Profile',
        'as' => 'studio-artist.profile.edit',
        'namespace' => NULL,
        'prefix' => '/studio-artist',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio-artist.booking-requests' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio-artist/demandes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\StudioArtist\\BookingRequests@__invoke',
        'controller' => 'App\\Livewire\\StudioArtist\\BookingRequests',
        'as' => 'studio-artist.booking-requests',
        'namespace' => NULL,
        'prefix' => '/studio-artist',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio-artist.messages' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio-artist/messages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\StudioArtist\\Messages@__invoke',
        'controller' => 'App\\Livewire\\StudioArtist\\Messages',
        'as' => 'studio-artist.messages',
        'namespace' => NULL,
        'prefix' => '/studio-artist',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio-artist.settings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio-artist/parametres',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\StudioArtist\\Settings@__invoke',
        'controller' => 'App\\Livewire\\StudioArtist\\Settings',
        'as' => 'studio-artist.settings',
        'namespace' => NULL,
        'prefix' => '/studio-artist',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio-artist.calendar' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio-artist/calendar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'App\\Livewire\\StudioArtist\\Calendar@__invoke',
        'controller' => 'App\\Livewire\\StudioArtist\\Calendar',
        'as' => 'studio-artist.calendar',
        'namespace' => NULL,
        'prefix' => '/studio-artist',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio-artist.upgrade' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio-artist/upgrade',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:65:"function () {
        return \\view(\'professionnels.index\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010e80000000000000000";}}',
        'as' => 'studio-artist.upgrade',
        'namespace' => NULL,
        'prefix' => '/studio-artist',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio-artist.studio-artist.compliance' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio-artist/compliance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'App\\Http\\Middleware\\EnsureStudioCanOperate',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:69:"function () {
        return \\view(\'studio-artist.compliance\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010e60000000000000000";}}',
        'as' => 'studio-artist.studio-artist.compliance',
        'namespace' => NULL,
        'prefix' => '/studio-artist',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'marketplace.show.artist' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'artistes/{slug}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\MarketplaceController@show',
        'controller' => 'App\\Http\\Controllers\\MarketplaceController@show',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'marketplace.show.artist',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.pending-verification' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pierceur/pending-verification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:86:"function () {
    return \\view(\'auth.pending-verification\', [\'role\' => \'pierceur\']);
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000011130000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'pierceur.pending-verification',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.pending-verification' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/pending-verification',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:84:"function () {
    return \\view(\'auth.pending-verification\', [\'role\' => \'studio\']);
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010e30000000000000000";}}',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'studio.pending-verification',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.profile' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ProfileController@index',
        'controller' => 'App\\Http\\Controllers\\Client\\ProfileController@index',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'client.profile',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.settings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ProfileController@settings',
        'controller' => 'App\\Http\\Controllers\\Client\\ProfileController@settings',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'client.settings',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.gdpr.export' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/settings/export-gdpr',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'throttle:3,60',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ProfileController@exportGdpr',
        'controller' => 'App\\Http\\Controllers\\Client\\ProfileController@exportGdpr',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'client.gdpr.export',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.settings.update-avatar' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'client/settings/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ProfileController@updateAvatar',
        'controller' => 'App\\Http\\Controllers\\Client\\ProfileController@updateAvatar',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'client.settings.update-avatar',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.settings.delete-avatar' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'client/settings/avatar',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ProfileController@deleteAvatar',
        'controller' => 'App\\Http\\Controllers\\Client\\ProfileController@deleteAvatar',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'client.settings.delete-avatar',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.messages' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/messages',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ProfileController@messages',
        'controller' => 'App\\Http\\Controllers\\Client\\ProfileController@messages',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'client.messages',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.bookings' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'client/bookings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ProfileController@bookings',
        'controller' => 'App\\Http\\Controllers\\Client\\ProfileController@bookings',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'client.bookings',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'tattooer.delete-account' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'tattooer/delete-account',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\AccountController@destroyAccount',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\AccountController@destroyAccount',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'tattooer.delete-account',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pierceur.delete-account' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'pierceur/delete-account',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Tattooer\\AccountController@destroyAccount',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\AccountController@destroyAccount',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'pierceur.delete-account',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.delete-account' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'studio/delete-account',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@destroyAccount',
        'controller' => 'App\\Http\\Controllers\\Studio\\StudioBillingController@destroyAccount',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'studio.delete-account',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'client.delete-account' => 
    array (
      'methods' => 
      array (
        0 => 'DELETE',
      ),
      'uri' => 'client/delete-account',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientAccountController@destroyAccount',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientAccountController@destroyAccount',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'client.delete-account',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'webhooks.stripe' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'webhooks/stripe',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:60,1',
        ),
        'uses' => 'App\\Http\\Controllers\\StripeWebhookController@handleWebhook',
        'controller' => 'App\\Http\\Controllers\\StripeWebhookController@handleWebhook',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'webhooks.stripe',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'stripe.connect.return' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'stripe/connect/return/{artist}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
        ),
        'uses' => 'App\\Http\\Controllers\\StripeConnectController@returnFromOnboarding',
        'controller' => 'App\\Http\\Controllers\\StripeConnectController@returnFromOnboarding',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'stripe.connect.return',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'stripe.connect.refresh' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'stripe/connect/refresh/{artist}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
        ),
        'uses' => 'App\\Http\\Controllers\\StripeConnectController@refreshOnboarding',
        'controller' => 'App\\Http\\Controllers\\StripeConnectController@refreshOnboarding',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'stripe.connect.refresh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.artist.stripe.return' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/artist/stripe/return',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
        ),
        'uses' => 'App\\Http\\Controllers\\StripeConnectController@studioArtistReturn',
        'controller' => 'App\\Http\\Controllers\\StripeConnectController@studioArtistReturn',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'studio.artist.stripe.return',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.artist.stripe.refresh' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/artist/stripe/refresh',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
        ),
        'uses' => 'App\\Http\\Controllers\\StripeConnectController@studioArtistRefresh',
        'controller' => 'App\\Http\\Controllers\\StripeConnectController@studioArtistRefresh',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'studio.artist.stripe.refresh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.stripe.return' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/stripe/return',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
        ),
        'uses' => 'App\\Http\\Controllers\\StripeConnectController@studioOwnerReturn',
        'controller' => 'App\\Http\\Controllers\\StripeConnectController@studioOwnerReturn',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'studio.stripe.return',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'studio.stripe.refresh' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'studio/stripe/refresh',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
        ),
        'uses' => 'App\\Http\\Controllers\\StripeConnectController@studioOwnerRefresh',
        'controller' => 'App\\Http\\Controllers\\StripeConnectController@studioOwnerRefresh',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'studio.stripe.refresh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'deposit.payment' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'deposit/{bookingRequest}/payment',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DepositController@payment',
        'controller' => 'App\\Http\\Controllers\\DepositController@payment',
        'as' => 'deposit.payment',
        'namespace' => NULL,
        'prefix' => '/deposit',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'deposit.process' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'deposit/{bookingRequest}/process',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DepositController@process',
        'controller' => 'App\\Http\\Controllers\\DepositController@process',
        'as' => 'deposit.process',
        'namespace' => NULL,
        'prefix' => '/deposit',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'deposit.success' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'deposit/{bookingRequest}/success',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DepositController@success',
        'controller' => 'App\\Http\\Controllers\\DepositController@success',
        'as' => 'deposit.success',
        'namespace' => NULL,
        'prefix' => '/deposit',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'deposit.cancel' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'deposit/{bookingRequest}/cancel',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\DepositController@cancel',
        'controller' => 'App\\Http\\Controllers\\DepositController@cancel',
        'as' => 'deposit.cancel',
        'namespace' => NULL,
        'prefix' => '/deposit',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'booking-request.success' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'booking-request/success',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:68:"function () {
        return \\view(\'booking-request.success\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010c90000000000000000";}}',
        'as' => 'booking-request.success',
        'namespace' => NULL,
        'prefix' => '/booking-request',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'booking-request.form' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'booking-request/{bookableId}/{bookableType}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:142:"function (int $bookableId, string $bookableType) {
        return \\view(\'booking-request-form\', \\compact(\'bookableId\', \'bookableType\'));
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010c70000000000000000";}}',
        'as' => 'booking-request.form',
        'namespace' => NULL,
        'prefix' => '/booking-request',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'bookableId' => '[0-9]+',
        'bookableType' => 'tattooer|Piercer|piercer|studio-artist',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'conversation.chat.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'conversation/{conversation}/chat',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientMessageController@chat',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientMessageController@chat',
        'as' => 'conversation.chat.show',
        'namespace' => NULL,
        'prefix' => '/conversation/{conversation}/chat',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'admin.conversation.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/conversation/{conversation}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'App\\Http\\Middleware\\EnsureUserIsAdmin',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:99:"function ($conversation) {
        return \\redirect(\'/admin/conversations/\' . $conversation);
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010c40000000000000000";}}',
        'as' => 'admin.conversation.show',
        'namespace' => NULL,
        'prefix' => '/admin/conversation',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'consent.store' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'consent/{bookingRequest}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\Client\\ClientSocialController@storeConsent',
        'controller' => 'App\\Http\\Controllers\\Client\\ClientSocialController@storeConsent',
        'as' => 'consent.store',
        'namespace' => NULL,
        'prefix' => '/consent',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.login' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'auth/login',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:55:"function () {
        return \\view(\'auth.login\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010c10000000000000000";}}',
        'as' => 'auth.login',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.password.request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'auth/forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ForgotPasswordController@showForgotForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\ForgotPasswordController@showForgotForm',
        'as' => 'auth.password.request',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.password.reset' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'auth/reset-password/{token}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ForgotPasswordController@showResetForm',
        'controller' => 'App\\Http\\Controllers\\Auth\\ForgotPasswordController@showResetForm',
        'as' => 'auth.password.reset',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.password.email' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'auth/forgot-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:5,10',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ForgotPasswordController@sendResetLink',
        'controller' => 'App\\Http\\Controllers\\Auth\\ForgotPasswordController@sendResetLink',
        'as' => 'auth.password.email',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'auth.password.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'auth/reset-password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'throttle:5,10',
        ),
        'uses' => 'App\\Http\\Controllers\\Auth\\ForgotPasswordController@resetPassword',
        'controller' => 'App\\Http\\Controllers\\Auth\\ForgotPasswordController@resetPassword',
        'as' => 'auth.password.update',
        'namespace' => NULL,
        'prefix' => '/auth',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'booking-request.deposit.request' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'booking-request/{bookingRequest}/deposit/request',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Livewire\\RequestDeposit@__invoke',
        'controller' => 'App\\Livewire\\RequestDeposit',
        'as' => 'booking-request.deposit.request',
        'namespace' => NULL,
        'prefix' => '/booking-request/{bookingRequest}/deposit',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'legal.mentions-legales' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'legal/mentions-legales',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\LegalController@mentionsLegales',
        'controller' => 'App\\Http\\Controllers\\LegalController@mentionsLegales',
        'as' => 'legal.mentions-legales',
        'namespace' => NULL,
        'prefix' => '/legal',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'legal.cgu' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'legal/cgu',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\LegalController@cgu',
        'controller' => 'App\\Http\\Controllers\\LegalController@cgu',
        'as' => 'legal.cgu',
        'namespace' => NULL,
        'prefix' => '/legal',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'legal.cgv-artistes' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'legal/cgv-artistes',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\LegalController@cgvArtistes',
        'controller' => 'App\\Http\\Controllers\\LegalController@cgvArtistes',
        'as' => 'legal.cgv-artistes',
        'namespace' => NULL,
        'prefix' => '/legal',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'legal.cgv-clients' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'legal/cgv-clients',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\LegalController@cgvClients',
        'controller' => 'App\\Http\\Controllers\\LegalController@cgvClients',
        'as' => 'legal.cgv-clients',
        'namespace' => NULL,
        'prefix' => '/legal',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'legal.politique-confidentialite' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'legal/politique-de-confidentialite',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\LegalController@politiqueConfidentialite',
        'controller' => 'App\\Http\\Controllers\\LegalController@politiqueConfidentialite',
        'as' => 'legal.politique-confidentialite',
        'namespace' => NULL,
        'prefix' => '/legal',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'legal.politique-cookies' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'legal/politique-de-cookies',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
        ),
        'uses' => 'App\\Http\\Controllers\\LegalController@politiqueCookies',
        'controller' => 'App\\Http\\Controllers\\LegalController@politiqueCookies',
        'as' => 'legal.politique-cookies',
        'namespace' => NULL,
        'prefix' => '/legal',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pdf.care-sheet' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pdf/care-sheet/{careSheet}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PdfExportController@careSheet',
        'controller' => 'App\\Http\\Controllers\\PdfExportController@careSheet',
        'as' => 'pdf.care-sheet',
        'namespace' => NULL,
        'prefix' => '/pdf',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pdf.consent-form' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pdf/consent-form/{consentForm}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PdfExportController@consentForm',
        'controller' => 'App\\Http\\Controllers\\PdfExportController@consentForm',
        'as' => 'pdf.consent-form',
        'namespace' => NULL,
        'prefix' => '/pdf',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pdf.parental-consent' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pdf/parental-consent/{parentalConsent}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PdfExportController@parentalConsent',
        'controller' => 'App\\Http\\Controllers\\PdfExportController@parentalConsent',
        'as' => 'pdf.parental-consent',
        'namespace' => NULL,
        'prefix' => '/pdf',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pdf.traceability' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pdf/traceability/{traceabilityRecord}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PdfExportController@traceabilityRecord',
        'controller' => 'App\\Http\\Controllers\\PdfExportController@traceabilityRecord',
        'as' => 'pdf.traceability',
        'namespace' => NULL,
        'prefix' => '/pdf',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pdf.client-summary' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pdf/client-summary/{client}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PdfExportController@clientSummary',
        'controller' => 'App\\Http\\Controllers\\PdfExportController@clientSummary',
        'as' => 'pdf.client-summary',
        'namespace' => NULL,
        'prefix' => '/pdf',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'pdf.receipt' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'pdf/receipt/{booking}',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
        ),
        'uses' => 'App\\Http\\Controllers\\PdfExportController@receipt',
        'controller' => 'App\\Http\\Controllers\\PdfExportController@receipt',
        'as' => 'pdf.receipt',
        'namespace' => NULL,
        'prefix' => '/pdf',
        'where' => 
        array (
        ),
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HUdKgbOwoS0oDiOE' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
        2 => 'POST',
        3 => 'PUT',
        4 => 'PATCH',
        5 => 'DELETE',
        6 => 'OPTIONS',
      ),
      'uri' => 'settings',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
        ),
        'uses' => '\\Illuminate\\Routing\\RedirectController@__invoke',
        'controller' => '\\Illuminate\\Routing\\RedirectController',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'generated::HUdKgbOwoS0oDiOE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
        'destination' => 'settings/profile',
        'status' => 302,
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'profile.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'settings/profile',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
        ),
        'uses' => 'App\\Livewire\\Settings\\Profile@__invoke',
        'controller' => 'App\\Livewire\\Settings\\Profile',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'profile.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'user-password.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'settings/password',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
        ),
        'uses' => 'App\\Livewire\\Settings\\Password@__invoke',
        'controller' => 'App\\Livewire\\Settings\\Password',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'user-password.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'appearance.edit' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'settings/appearance',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
        ),
        'uses' => 'App\\Livewire\\Settings\\Appearance@__invoke',
        'controller' => 'App\\Livewire\\Settings\\Appearance',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'appearance.edit',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'two-factor.show' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'settings/two-factor',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'web',
          1 => 'auth',
          2 => 'verified',
        ),
        'uses' => 'App\\Livewire\\Settings\\TwoFactor@__invoke',
        'controller' => 'App\\Livewire\\Settings\\TwoFactor',
        'namespace' => NULL,
        'prefix' => '',
        'where' => 
        array (
        ),
        'as' => 'two-factor.show',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'storage.local' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'storage/{path}',
      'action' => 
      array (
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:3:{s:4:"disk";s:5:"local";s:6:"config";a:5:{s:6:"driver";s:5:"local";s:4:"root";s:49:"C:\\laragon\\www\\tattoolib-saas\\storage\\app/private";s:5:"serve";b:1;s:5:"throw";b:0;s:6:"report";b:0;}s:12:"isProduction";b:0;}s:8:"function";s:323:"function (\\Illuminate\\Http\\Request $request, string $path) use ($disk, $config, $isProduction) {
                    return (new \\Illuminate\\Filesystem\\ServeFile(
                        $disk,
                        $config,
                        $isProduction
                    ))($request, $path);
                }";s:5:"scope";s:47:"Illuminate\\Filesystem\\FilesystemServiceProvider";s:4:"this";N;s:4:"self";s:32:"00000000000010d40000000000000000";}}',
        'as' => 'storage.local',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
        'path' => '.*',
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'livewire.update' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'livewire/update',
      'action' => 
      array (
        'uses' => 'Livewire\\Mechanisms\\HandleRequests\\HandleRequests@handleUpdate',
        'controller' => 'Livewire\\Mechanisms\\HandleRequests\\HandleRequests@handleUpdate',
        'middleware' => 
        array (
          0 => 'web',
        ),
        'as' => 'livewire.update',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
  ),
)
);
