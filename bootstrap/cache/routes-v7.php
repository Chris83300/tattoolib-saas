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
      '/admin/financial-reports' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'filament.admin.pages.financial-reports',
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
            '_route' => 'generated::LvfL7OW1De2MIdxA',
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
            '_route' => 'generated::JTUMXkFpzhwuwsEz',
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
            '_route' => 'generated::SjbALApfSmuJlCDZ',
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
            '_route' => 'generated::aVWOtbIsZJzzNPT7',
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
            '_route' => 'generated::dda6ZL848kdMllyK',
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
            '_route' => 'generated::gchBa2uLDewRMkj4',
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
            '_route' => 'generated::HNk9CtqA6TUNiebG',
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
            '_route' => 'generated::YhHeJOyIDSSPKwOb',
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
            '_route' => 'generated::cCsDQryQchzerYjS',
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
            '_route' => 'generated::EW1AJx7snzqBHniP',
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
            '_route' => 'generated::FO36onJBl5YF5RO4',
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
            '_route' => 'generated::ySkLPmHrX2hkxqSw',
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
            '_route' => 'generated::ZjA1P6JLDCtJaTpQ',
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
            '_route' => 'generated::CZIhP5AQLxzwLTie',
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
            '_route' => 'generated::BmwTAcMuRsxeYHKp',
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
            '_route' => 'generated::scGmg2jNbzOJOkp3',
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
            '_route' => 'generated::bFI2DMtXMC80aKAo',
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
            '_route' => 'generated::IgIuZBEvg0qhbHOi',
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
            '_route' => 'generated::KWKY7JRHSYpAi7WZ',
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
      '/api/stripe/webhook' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gv1GAyhyh13RW7hN',
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
      '/api/booking-requests' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::71dERkfQjV01Xzdt',
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
            '_route' => 'generated::pVdPG3YrsQsEWd8r',
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
            '_route' => 'generated::uoKpz42ZY3bdKZ59',
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
            '_route' => 'generated::mUsTiYk1v5ok5dbs',
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
            '_route' => 'generated::oAJOkXzKH3zEh3tp',
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
            '_route' => 'generated::qa0vLu1i4hDpbXu1',
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
            '_route' => 'generated::15sK3tlSD2wKlEGC',
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
            '_route' => 'generated::KinGpN98R9CiYJqf',
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
            '_route' => 'generated::BLAc2R8nujQWlxCS',
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
            '_route' => 'generated::dRGuNFzG8gv3NNDD',
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
            '_route' => 'generated::cCqTyfm2piADnFxd',
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
            '_route' => 'generated::KIWtZcALUK5qztgL',
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
            '_route' => 'generated::3pXiuskKZrCwCws4',
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
            '_route' => 'generated::EaJwTTVi9h6KrhE6',
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
            '_route' => 'generated::BRRLgyT2D9KT7zXV',
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
            '_route' => 'generated::wD4W5aFc1ev4nVVz',
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
            '_route' => 'generated::aXN1MhpTyEdr9cK3',
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
            '_route' => 'generated::4Lf3dd79mFSaCSM2',
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
            '_route' => 'generated::EJqJtGpsWntlIChA',
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
            '_route' => 'generated::IAYGzfhkCTLaWaLf',
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
            '_route' => 'generated::Tdbv3xlBT6mqAXVR',
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
            '_route' => 'generated::YReTomakMTfe2JFp',
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
            '_route' => 'generated::uLMDCAs1XeRxZFl6',
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
            '_route' => 'generated::ZTdRn7It0ilOSiAZ',
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
            '_route' => 'generated::CW2cimMa4AuX3xJF',
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
            '_route' => 'generated::GCUNqKd23y6nWwM5',
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
            '_route' => 'generated::kfGn8lW0gTCiXUcx',
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
            '_route' => 'generated::dv1LVpou03vh5JV7',
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
            '_route' => 'generated::50exziXVKwDu0uSJ',
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
            '_route' => 'generated::qavyRe6c8mCoPt4Z',
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
            '_route' => 'generated::LfLPW5i5jC9sg8pf',
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
            '_route' => 'generated::3KngNs19XeqTEx6N',
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
            '_route' => 'generated::MxUcsyWsbQUsCG3i',
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
            '_route' => 'generated::WCeEoM7AcFBiz8Z5',
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
            '_route' => 'generated::Jq1uaVfy0oVBpl1B',
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
            '_route' => 'generated::JCe2uLMzRwEyb1Ib',
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
            '_route' => 'generated::meD3jVQ8yVNwbKhY',
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
            '_route' => 'tattooer.tattooer.pricing',
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
            '_route' => 'tattooer.tattooer.settings.hours.update',
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
            '_route' => 'studio.studio.compliance',
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
      '/settings' => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::JlVkn3XcqIL3nFKB',
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
      0 => '{^(?|/filament/(?|exports/([^/]++)/download(*:45)|imports/([^/]++)/failed\\-rows/download(*:90))|/a(?|dmin/(?|booking\\-requests/([^/]++)/edit(*:142)|compl(?|aints/([^/]++)/edit(*:177)|iance\\-records/([^/]++)/edit(*:213))|p(?|ayments/([^/]++)(?|(*:245)|/edit(*:258))|ierceurs/([^/]++)/edit(*:289))|reviews/([^/]++)/edit(*:319)|studio(?|\\-artists/([^/]++)(?|(*:357)|/edit(*:370))|s/([^/]++)(?|(*:392)|/edit(*:405)))|t(?|attooers/([^/]++)/edit(*:441)|ransactions/([^/]++)/edit(*:474))|users/([^/]++)/edit(*:502))|pi/(?|t(?|attooers/([^/]++)(?|(*:541)|/(?|portfolio(?|(*:565)|(*:573)|/([^/]++)(*:590))|availability(*:611)|working\\-hours(?|(*:636)|/([0-6])(*:652))))|raceability/(?|consent\\-forms/([^/]++)/(?|verify(*:711)|parental\\-consent(*:736))|records/([^/]++)/(?|materials(*:774)|photos(*:788)|verify(*:802)|report(*:816))))|c(?|onversations/([^/]++)(?|(*:855)|/(?|m(?|ark\\-as\\-read(*:884)|essages(?|(*:902)|/(?|([^/]++)(*:922)|search(*:936))))|toggle\\-mute(*:959)|archive(*:974)|block(*:987)|design\\-versions(*:1011)))|are\\-sheets/(?|([^/]++)(?|(*:1048)|/photos(*:1064))|appointments/([^/]++)/create(*:1102)))|messages/([^/]++)/download(*:1139)|booking(?|s/([^/]++)(?|/(?|payment/deposit(*:1190)|accept(*:1205)|reject(*:1220)|c(?|onfirm\\-appointment(*:1252)|ancel(*:1266)))|(*:1277))|\\-requests/(?|([^/]++)(?|(*:1312)|/(?|accept(*:1331)|reject(*:1346)|confirm\\-deposit(*:1371)|send\\-design(*:1392)))|check\\-expired(*:1417)|([^/]++)/(?|c(?|onfirm\\-appointment(*:1461)|ancel(*:1475))|mark\\-deposit\\-paid(*:1504))))|a(?|ppointments/([^/]++)(?|(*:1543)|/(?|c(?|onfirm\\-completion(*:1578)|ancel(*:1592))|report\\-(?|no\\-show(*:1621)|issue(*:1635))|dispute\\-refund(*:1660)))|vailabilities/(?|([^/]++)(?|(*:1699))|generate\\-from\\-working\\-hours(*:1739)|check(*:1753))|ccounting/transactions/([^/]++)/mark\\-paid(*:1805))|planning/(?|release\\-slot/([^/]++)(*:1849)|tattooers/([^/]++)/(?|available\\-dates(*:1896)|slots\\-for\\-date(*:1921)))|inventory/([^/]++)(?|(*:1953)|/(?|movement(?|(*:1977)|s(*:1987))|adjust(*:2003))))|rtistes/([^/]++)(*:2031)|uth/reset\\-password/([^/]++)(*:2068))|/s(?|t(?|ripe/payment/([^/]++)(*:2108)|udio(?|s/([^/]++)(*:2134)|/(?|invitation/([^/]++)(?|(*:2169))|artists/([^/]++)(?|(*:2198)|/toggle(*:2214))))|orage/(.*)(*:2236))|alon/([^/]++)(*:2259))|/reset\\-password/([^/]++)(*:2294)|/email/verify/([^/]++)/([^/]++)(*:2334)|/livewire/preview\\-file/([^/]++)(*:2375)|/tattooer/(?|requests/([^/]++)(?|(*:2417)|/(?|accept(?|(*:2439))|reject(*:2455)))|booking(?|\\-requests/([^/]++)/(?|repropose\\-dates(*:2515)|complete(*:2532)|no\\-show(*:2549))|s/([^/]++)/balance/confirm\\-offline(*:2594))|c(?|alendar/([^/]++)(?|(*:2627))|lient(?|s/([^/]++)(?|(*:2658)|/(?|consent/(?|upload(*:2688)|store\\-digital(*:2711)|([^/]++)(*:2728))|traceability(*:2750)|photos/(?|upload(*:2775)|([^/]++)(*:2792))|notes(*:2807)|requests(*:2824)))|/([^/]++)/(?|photos/([^/]++)(*:2863)|media/([^/]++)(*:2886)))|onsent/([^/]++)(*:2912))|message(?|s/([^/]++)(*:2942)|/([^/]++)/send(*:2965))|traceability/([^/]++)(*:2996)|appointments/([^/]++)/(?|complete(*:3038)|no\\-show(*:3055))|portfolio/(?|([^/]++)(*:3086)|before\\-after/([^/]++)/([^/]++)(*:3126))|([^/]++)(*:3144)|delete\\-account(*:3168))|/pierce(?|r/([^/]++)(*:3198)|ur/(?|requests/([^/]++)(?|(*:3233)|/(?|accept(?|(*:3255))|reject(*:3271)))|booking(?|\\-requests/([^/]++)/(?|repropose\\-dates(*:3331)|complete(*:3348)|no\\-show(*:3365))|s/([^/]++)/balance/confirm\\-offline(*:3410))|c(?|alendar/([^/]++)(?|(*:3443))|lient(?|s/([^/]++)(?|(*:3474)|/(?|consent/(?|upload(*:3504)|store\\-digital(*:3527)|([^/]++)(*:3544))|traceability(*:3566)|photos/(?|upload(*:3591)|([^/]++)(*:3608))|notes(*:3623)|requests(*:3640)))|/([^/]++)/(?|photos/([^/]++)(*:3679)|media/([^/]++)(*:3702)))|onsent/([^/]++)(*:3728))|message(?|s/([^/]++)(*:3758)|/([^/]++)/send(*:3781))|traceability/([^/]++)(*:3812)|appointments/([^/]++)/(?|complete(*:3854)|no\\-show(*:3871))|portfolio/([^/]++)(*:3899)))|/c(?|lient/(?|booking(?|\\-request(?|s/([^/]++)(?|(*:3959)|/(?|select\\-date(*:3984)|request\\-alternatives(*:4014)|cancel(*:4029)))|/([^/]++)/delete(*:4056))|s/([^/]++)/balance(*:4084))|c(?|hat/([^/]++)(*:4110)|omplaints/([^/]++)(*:4137))|message/([^/]++)/send(*:4168)|reviews/([^/]++)(*:4193))|onversation/([^/]++)/chat(*:4228))|/deposit/([^/]++)/(?|p(?|ayment(*:4269)|rocess(*:4284))|success(*:4301)|cancel(*:4316))|/booking\\-request/(?|([0-9]+)/(tattooer|Piercer|piercer|studio-artist)(*:4396)|([^/]++)/deposit/request(*:4429)))/?$}sDu',
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
      177 => 
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
      213 => 
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
      245 => 
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
      258 => 
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
      289 => 
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
      319 => 
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
      357 => 
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
      370 => 
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
      392 => 
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
      405 => 
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
      441 => 
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
      474 => 
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
      502 => 
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
      541 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::jsqoGwlIZaKiKfTD',
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
      565 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::TWxvEz8qdVw5u4MA',
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
      573 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::ZFPSDJv5Cij4cZpJ',
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
      590 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3XNzOb3PF5eHCcjq',
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
      611 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yK4USOawVlvpEU7o',
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
      636 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::bNaNZHYMTrMeVX44',
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
            '_route' => 'generated::bxUWmRQlbi5ANxhc',
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
      652 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6ZawX3NcfzCEwzKQ',
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
      711 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::eqol8Oyv8C85uxv5',
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
      736 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BqpI4apTebUQQpea',
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
      774 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::6ym6NZmxTNQYExlg',
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
      788 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2wpNbTzHKVQUsfRN',
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
      802 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2ydAeS3jp0kltHVY',
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
      816 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::VjfJEzfyNU14P14n',
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
      855 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::uLhmBo5GFqMT1CUB',
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
      884 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::vs9HwHjixdg5ye0m',
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
      902 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PQo9CQYXCTcs5F7f',
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
            '_route' => 'generated::LkEQMBKwxDJq6LLn',
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
      922 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::BJ5xgiVtoOE02U8f',
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
      936 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::kgg88du1cqNoQ8VW',
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
      959 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::7Psu2qOi11FIcTeL',
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
      974 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::0O5N8bTAmWr0nRkk',
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
      987 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::cEZ5vWguRXpa3A7S',
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
      1011 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::aRHIKw7tSXNK2KTp',
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
      1048 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1Nn4IRyglueDdaik',
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
            '_route' => 'generated::2ALD7GmA2V4nNbCC',
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
      1064 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::remxSyBmTEjpBcfV',
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
      1102 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::PhBTCgZFR5fd9vY2',
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
      1139 => 
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
      1190 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::3IDe2HytGXoHZ1y0',
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
      1205 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::XLpUiFHbwEhiuBtG',
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
      1220 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::wkfMi19tv6TOCWrm',
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
      1252 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::w1fI1rRTriW3YOT9',
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
      1266 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::1bASHthmDDNLuAmD',
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
      1277 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::f5mod3wbnsk3YcsN',
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
      1312 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::56qhQQoHbzFbCOpQ',
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
      1331 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::imS0WLOJeGbWGszu',
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
      1346 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Q4V1G7nrzaUg0NR0',
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
      1371 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::A47zP7Xk5O2GjAO0',
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
      1392 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::MX9GiZYoQrGgCDKG',
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
      1417 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::gEpJXNX5OEE73TMn',
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
      1461 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::TPFoiPvXdzZyS6fA',
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
      1475 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::bvJCDu0yz0gU3QBV',
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
      1504 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::7uAJueTBCvEX16pl',
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
      1543 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::5hsgcxtOO981o3b1',
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
      1578 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::O0zi60Too6pL3yYh',
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
      1592 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::2M3gVYW5dpYXSkXp',
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
      1621 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::S4rBHEpqcFCbD6GM',
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
      1635 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::yv74D0tb0jxpkC2R',
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
      1660 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::HF6r3tOJjyoANS0U',
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
      1699 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::arXAg4ixrq2NM1jB',
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
            '_route' => 'generated::Ozkw9sqhqIpjK9ZS',
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
      1739 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Osi5IrROkZfc5GeA',
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
      1753 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::fKY0w9Pty7VtI6r1',
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
      1805 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::kjxJqmJVjtQFK2mE',
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
      1849 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::KzwYfuB73gyH6sfP',
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
      1896 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::j1PwAoQ49L4Y0ni5',
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
      1921 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::rmHhmEyZilZ8StLp',
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
      1953 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::97ydxQ1IWiFEzWAx',
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
            '_route' => 'generated::PnXa16yYvOvaHmdd',
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
      1977 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Q8RwqASM4EFXxffG',
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
      1987 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::Dy7TbeSPLpow7fDc',
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
      2003 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'generated::g06P1upBUKgDsYxm',
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
      2031 => 
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
      2068 => 
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
      2108 => 
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
      2134 => 
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
      2169 => 
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
      2198 => 
      array (
        0 => 
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
      2214 => 
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
      2236 => 
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
      2259 => 
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
      2294 => 
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
      2334 => 
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
      2375 => 
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
      2417 => 
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
      2439 => 
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
      2455 => 
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
      2515 => 
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
      2532 => 
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
      2549 => 
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
      2594 => 
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
      2627 => 
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
      2658 => 
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
      2688 => 
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
      2711 => 
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
      2728 => 
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
      2750 => 
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
      2775 => 
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
      2792 => 
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
      2807 => 
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
      2824 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.tattooer.client-requests',
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
      2863 => 
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
      2886 => 
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
      2912 => 
      array (
        0 => 
        array (
          0 => 
          array (
            '_route' => 'tattooer.tattooer.consent.store',
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
      2942 => 
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
      2965 => 
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
      2996 => 
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
      3038 => 
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
      3055 => 
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
      3086 => 
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
      3126 => 
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
      3144 => 
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
      3168 => 
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
      3198 => 
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
      3233 => 
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
      3255 => 
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
      3271 => 
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
      3331 => 
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
      3348 => 
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
      3365 => 
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
      3410 => 
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
      3443 => 
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
      3474 => 
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
      3504 => 
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
      3527 => 
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
      3544 => 
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
      3566 => 
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
      3591 => 
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
      3608 => 
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
      3623 => 
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
      3640 => 
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
      3679 => 
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
      3702 => 
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
      3728 => 
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
      3758 => 
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
      3781 => 
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
      3812 => 
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
      3854 => 
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
      3871 => 
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
      3899 => 
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
      3959 => 
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
      3984 => 
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
      4014 => 
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
      4029 => 
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
      4056 => 
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
      4084 => 
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
      4110 => 
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
      4137 => 
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
      4168 => 
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
      4193 => 
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
      4228 => 
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
      4269 => 
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
      4284 => 
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
      4301 => 
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
      4316 => 
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
      4396 => 
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
      4429 => 
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
    'filament.admin.pages.financial-reports' => 
    array (
      'methods' => 
      array (
        0 => 'GET',
        1 => 'HEAD',
      ),
      'uri' => 'admin/financial-reports',
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
        'uses' => 'App\\Filament\\Admin\\Pages\\FinancialReports@__invoke',
        'controller' => 'App\\Filament\\Admin\\Pages\\FinancialReports',
        'as' => 'filament.admin.pages.financial-reports',
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
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010a30000000000000000";}}',
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
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:792:"function () {
    // Si déjà connecté, rediriger vers le profil approprié
    if (\\auth()->check()) {
        $user = \\auth()->user();
        switch ($user->role) {
            case \'client\':
                return \\redirect()->route(\'client.profile\');
            case \'tattooer\':
                return \\redirect()->route(\'tattooer.dashboard\');
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
    return \\view(\'auth.register\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010a10000000000000000";}}',
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
          2 => 'password.confirm',
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
          2 => 'password.confirm',
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
          2 => 'password.confirm',
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
          2 => 'password.confirm',
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
          2 => 'password.confirm',
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
          2 => 'password.confirm',
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
          2 => 'password.confirm',
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
    'generated::LvfL7OW1De2MIdxA' => 
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
        'as' => 'generated::LvfL7OW1De2MIdxA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::JTUMXkFpzhwuwsEz' => 
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
        'as' => 'generated::JTUMXkFpzhwuwsEz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::SjbALApfSmuJlCDZ' => 
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
        'as' => 'generated::SjbALApfSmuJlCDZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aVWOtbIsZJzzNPT7' => 
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
        'as' => 'generated::aVWOtbIsZJzzNPT7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dda6ZL848kdMllyK' => 
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
        'as' => 'generated::dda6ZL848kdMllyK',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gchBa2uLDewRMkj4' => 
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
        'as' => 'generated::gchBa2uLDewRMkj4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HNk9CtqA6TUNiebG' => 
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
        'as' => 'generated::HNk9CtqA6TUNiebG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
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
    'generated::YhHeJOyIDSSPKwOb' => 
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
        'as' => 'generated::YhHeJOyIDSSPKwOb',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cCsDQryQchzerYjS' => 
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
        'as' => 'generated::cCsDQryQchzerYjS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EW1AJx7snzqBHniP' => 
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
        'as' => 'generated::EW1AJx7snzqBHniP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::jsqoGwlIZaKiKfTD' => 
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
        'as' => 'generated::jsqoGwlIZaKiKfTD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::TWxvEz8qdVw5u4MA' => 
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
        'as' => 'generated::TWxvEz8qdVw5u4MA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yK4USOawVlvpEU7o' => 
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
        'as' => 'generated::yK4USOawVlvpEU7o',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::FO36onJBl5YF5RO4' => 
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
        'as' => 'generated::FO36onJBl5YF5RO4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ySkLPmHrX2hkxqSw' => 
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
        'as' => 'generated::ySkLPmHrX2hkxqSw',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZjA1P6JLDCtJaTpQ' => 
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
        'as' => 'generated::ZjA1P6JLDCtJaTpQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CZIhP5AQLxzwLTie' => 
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
        'as' => 'generated::CZIhP5AQLxzwLTie',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BmwTAcMuRsxeYHKp' => 
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
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"0000000000000f220000000000000000";}}',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'as' => 'generated::BmwTAcMuRsxeYHKp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::scGmg2jNbzOJOkp3' => 
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
        'as' => 'generated::scGmg2jNbzOJOkp3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bFI2DMtXMC80aKAo' => 
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
        'as' => 'generated::bFI2DMtXMC80aKAo',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IgIuZBEvg0qhbHOi' => 
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
        'as' => 'generated::IgIuZBEvg0qhbHOi',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KWKY7JRHSYpAi7WZ' => 
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
        'as' => 'generated::KWKY7JRHSYpAi7WZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uLhmBo5GFqMT1CUB' => 
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
        'as' => 'generated::uLhmBo5GFqMT1CUB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::vs9HwHjixdg5ye0m' => 
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
        'as' => 'generated::vs9HwHjixdg5ye0m',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7Psu2qOi11FIcTeL' => 
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
        'as' => 'generated::7Psu2qOi11FIcTeL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::0O5N8bTAmWr0nRkk' => 
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
        'as' => 'generated::0O5N8bTAmWr0nRkk',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cEZ5vWguRXpa3A7S' => 
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
        'as' => 'generated::cEZ5vWguRXpa3A7S',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PQo9CQYXCTcs5F7f' => 
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
        'as' => 'generated::PQo9CQYXCTcs5F7f',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LkEQMBKwxDJq6LLn' => 
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
        'as' => 'generated::LkEQMBKwxDJq6LLn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BJ5xgiVtoOE02U8f' => 
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
        'as' => 'generated::BJ5xgiVtoOE02U8f',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aRHIKw7tSXNK2KTp' => 
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
        'as' => 'generated::aRHIKw7tSXNK2KTp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kgg88du1cqNoQ8VW' => 
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
        'as' => 'generated::kgg88du1cqNoQ8VW',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
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
    'generated::3IDe2HytGXoHZ1y0' => 
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
        'as' => 'generated::3IDe2HytGXoHZ1y0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gv1GAyhyh13RW7hN' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/stripe/webhook',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\StripeWebhookController@handleWebhook',
        'controller' => 'App\\Http\\Controllers\\StripeWebhookController@handleWebhook',
        'namespace' => NULL,
        'prefix' => 'api',
        'where' => 
        array (
        ),
        'excluded_middleware' => 
        array (
          0 => 'Illuminate\\Foundation\\Http\\Middleware\\VerifyCsrfToken',
        ),
        'as' => 'generated::gv1GAyhyh13RW7hN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZFPSDJv5Cij4cZpJ' => 
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
        'as' => 'generated::ZFPSDJv5Cij4cZpJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3XNzOb3PF5eHCcjq' => 
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
        'as' => 'generated::3XNzOb3PF5eHCcjq',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bNaNZHYMTrMeVX44' => 
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
        'as' => 'generated::bNaNZHYMTrMeVX44',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bxUWmRQlbi5ANxhc' => 
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
        'as' => 'generated::bxUWmRQlbi5ANxhc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6ZawX3NcfzCEwzKQ' => 
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
        'as' => 'generated::6ZawX3NcfzCEwzKQ',
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
    'generated::71dERkfQjV01Xzdt' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@index',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::71dERkfQjV01Xzdt',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uoKpz42ZY3bdKZ59' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@statistics',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@statistics',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::uoKpz42ZY3bdKZ59',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::pVdPG3YrsQsEWd8r' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@store',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::pVdPG3YrsQsEWd8r',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::56qhQQoHbzFbCOpQ' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@show',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::56qhQQoHbzFbCOpQ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::imS0WLOJeGbWGszu' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@accept',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@accept',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::imS0WLOJeGbWGszu',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Q4V1G7nrzaUg0NR0' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@reject',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@reject',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::Q4V1G7nrzaUg0NR0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::A47zP7Xk5O2GjAO0' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@confirmDeposit',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@confirmDeposit',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::A47zP7Xk5O2GjAO0',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MX9GiZYoQrGgCDKG' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@sendDesign',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@sendDesign',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::MX9GiZYoQrGgCDKG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::gEpJXNX5OEE73TMn' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@checkExpiredRequests',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@checkExpiredRequests',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::gEpJXNX5OEE73TMn',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::TPFoiPvXdzZyS6fA' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@confirmAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@confirmAppointment',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::TPFoiPvXdzZyS6fA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::bvJCDu0yz0gU3QBV' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@cancel',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@cancel',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::bvJCDu0yz0gU3QBV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::7uAJueTBCvEX16pl' => 
    array (
      'methods' => 
      array (
        0 => 'POST',
      ),
      'uri' => 'api/booking-requests/{bookingRequest}/mark-deposit-paid',
      'action' => 
      array (
        'middleware' => 
        array (
          0 => 'api',
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@markDepositPaid',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@markDepositPaid',
        'namespace' => NULL,
        'prefix' => 'api/booking-requests',
        'where' => 
        array (
        ),
        'as' => 'generated::7uAJueTBCvEX16pl',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::mUsTiYk1v5ok5dbs' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@index',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::mUsTiYk1v5ok5dbs',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::oAJOkXzKH3zEh3tp' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@store',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::oAJOkXzKH3zEh3tp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::f5mod3wbnsk3YcsN' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@show',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::f5mod3wbnsk3YcsN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::XLpUiFHbwEhiuBtG' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@accept',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@accept',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::XLpUiFHbwEhiuBtG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wkfMi19tv6TOCWrm' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@reject',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@reject',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::wkfMi19tv6TOCWrm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::w1fI1rRTriW3YOT9' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@confirmAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@confirmAppointment',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::w1fI1rRTriW3YOT9',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1bASHthmDDNLuAmD' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\BookingRequestController@cancel',
        'controller' => 'App\\Http\\Controllers\\Api\\BookingRequestController@cancel',
        'namespace' => NULL,
        'prefix' => 'api/bookings',
        'where' => 
        array (
        ),
        'as' => 'generated::1bASHthmDDNLuAmD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qa0vLu1i4hDpbXu1' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@index',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::qa0vLu1i4hDpbXu1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::15sK3tlSD2wKlEGC' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@upcoming',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@upcoming',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::15sK3tlSD2wKlEGC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KinGpN98R9CiYJqf' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@past',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@past',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::KinGpN98R9CiYJqf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BLAc2R8nujQWlxCS' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@statistics',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@statistics',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::BLAc2R8nujQWlxCS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dRGuNFzG8gv3NNDD' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@requireConfirmation',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@requireConfirmation',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::dRGuNFzG8gv3NNDD',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::cCqTyfm2piADnFxd' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@calendar',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@calendar',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::cCqTyfm2piADnFxd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::5hsgcxtOO981o3b1' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@show',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::5hsgcxtOO981o3b1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::O0zi60Too6pL3yYh' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@confirmCompletion',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@confirmCompletion',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::O0zi60Too6pL3yYh',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::S4rBHEpqcFCbD6GM' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@reportNoShow',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@reportNoShow',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::S4rBHEpqcFCbD6GM',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::yv74D0tb0jxpkC2R' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@reportIssue',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@reportIssue',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::yv74D0tb0jxpkC2R',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2M3gVYW5dpYXSkXp' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@cancel',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@cancel',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::2M3gVYW5dpYXSkXp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::HF6r3tOJjyoANS0U' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AppointmentController@disputeRefund',
        'controller' => 'App\\Http\\Controllers\\Api\\AppointmentController@disputeRefund',
        'namespace' => NULL,
        'prefix' => 'api/appointments',
        'where' => 
        array (
        ),
        'as' => 'generated::HF6r3tOJjyoANS0U',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KIWtZcALUK5qztgL' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@index',
        'namespace' => NULL,
        'prefix' => 'api/availabilities',
        'where' => 
        array (
        ),
        'as' => 'generated::KIWtZcALUK5qztgL',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3pXiuskKZrCwCws4' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@store',
        'namespace' => NULL,
        'prefix' => 'api/availabilities',
        'where' => 
        array (
        ),
        'as' => 'generated::3pXiuskKZrCwCws4',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::arXAg4ixrq2NM1jB' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@update',
        'namespace' => NULL,
        'prefix' => 'api/availabilities',
        'where' => 
        array (
        ),
        'as' => 'generated::arXAg4ixrq2NM1jB',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Ozkw9sqhqIpjK9ZS' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@destroy',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@destroy',
        'namespace' => NULL,
        'prefix' => 'api/availabilities',
        'where' => 
        array (
        ),
        'as' => 'generated::Ozkw9sqhqIpjK9ZS',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Osi5IrROkZfc5GeA' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@generateFromWorkingHours',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@generateFromWorkingHours',
        'namespace' => NULL,
        'prefix' => 'api/availabilities',
        'where' => 
        array (
        ),
        'as' => 'generated::Osi5IrROkZfc5GeA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::fKY0w9Pty7VtI6r1' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AvailabilityController@checkAvailability',
        'controller' => 'App\\Http\\Controllers\\Api\\AvailabilityController@checkAvailability',
        'namespace' => NULL,
        'prefix' => 'api/availabilities',
        'where' => 
        array (
        ),
        'as' => 'generated::fKY0w9Pty7VtI6r1',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EaJwTTVi9h6KrhE6' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@dashboard',
        'namespace' => NULL,
        'prefix' => 'api/planning',
        'where' => 
        array (
        ),
        'as' => 'generated::EaJwTTVi9h6KrhE6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BRRLgyT2D9KT7zXV' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@blockSlot',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@blockSlot',
        'namespace' => NULL,
        'prefix' => 'api/planning',
        'where' => 
        array (
        ),
        'as' => 'generated::BRRLgyT2D9KT7zXV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::wD4W5aFc1ev4nVVz' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@createExternalAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@createExternalAppointment',
        'namespace' => NULL,
        'prefix' => 'api/planning',
        'where' => 
        array (
        ),
        'as' => 'generated::wD4W5aFc1ev4nVVz',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::KzwYfuB73gyH6sfP' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@releaseSlot',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@releaseSlot',
        'namespace' => NULL,
        'prefix' => 'api/planning',
        'where' => 
        array (
        ),
        'as' => 'generated::KzwYfuB73gyH6sfP',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::j1PwAoQ49L4Y0ni5' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@availableDates',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@availableDates',
        'namespace' => NULL,
        'prefix' => 'api/planning',
        'where' => 
        array (
        ),
        'as' => 'generated::j1PwAoQ49L4Y0ni5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::rmHhmEyZilZ8StLp' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@slotsForDate',
        'controller' => 'App\\Http\\Controllers\\Api\\TattooerPlanningController@slotsForDate',
        'namespace' => NULL,
        'prefix' => 'api/planning',
        'where' => 
        array (
        ),
        'as' => 'generated::rmHhmEyZilZ8StLp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::aXN1MhpTyEdr9cK3' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@index',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::aXN1MhpTyEdr9cK3',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::4Lf3dd79mFSaCSM2' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@store',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::4Lf3dd79mFSaCSM2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::EJqJtGpsWntlIChA' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@clientIndex',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@clientIndex',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::EJqJtGpsWntlIChA',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::IAYGzfhkCTLaWaLf' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@statistics',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@statistics',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::IAYGzfhkCTLaWaLf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::1Nn4IRyglueDdaik' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@show',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::1Nn4IRyglueDdaik',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2ALD7GmA2V4nNbCC' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@update',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::2ALD7GmA2V4nNbCC',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::remxSyBmTEjpBcfV' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@addPhoto',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@addPhoto',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::remxSyBmTEjpBcfV',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PhBTCgZFR5fd9vY2' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@createFromAppointment',
        'controller' => 'App\\Http\\Controllers\\Api\\ClientCareSheetController@createFromAppointment',
        'namespace' => NULL,
        'prefix' => 'api/care-sheets',
        'where' => 
        array (
        ),
        'as' => 'generated::PhBTCgZFR5fd9vY2',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Tdbv3xlBT6mqAXVR' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@index',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::Tdbv3xlBT6mqAXVR',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::YReTomakMTfe2JFp' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@store',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@store',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::YReTomakMTfe2JFp',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::uLMDCAs1XeRxZFl6' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@statistics',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@statistics',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::uLMDCAs1XeRxZFl6',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::ZTdRn7It0ilOSiAZ' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@alerts',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@alerts',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::ZTdRn7It0ilOSiAZ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::97ydxQ1IWiFEzWAx' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@show',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@show',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::97ydxQ1IWiFEzWAx',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::PnXa16yYvOvaHmdd' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@update',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@update',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::PnXa16yYvOvaHmdd',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Q8RwqASM4EFXxffG' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@movement',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@movement',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::Q8RwqASM4EFXxffG',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::g06P1upBUKgDsYxm' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@adjustStock',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@adjustStock',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::g06P1upBUKgDsYxm',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Dy7TbeSPLpow7fDc' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\InventoryController@movements',
        'controller' => 'App\\Http\\Controllers\\Api\\InventoryController@movements',
        'namespace' => NULL,
        'prefix' => 'api/inventory',
        'where' => 
        array (
        ),
        'as' => 'generated::Dy7TbeSPLpow7fDc',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::CW2cimMa4AuX3xJF' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@dashboard',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@dashboard',
        'namespace' => NULL,
        'prefix' => 'api/accounting',
        'where' => 
        array (
        ),
        'as' => 'generated::CW2cimMa4AuX3xJF',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::GCUNqKd23y6nWwM5' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@report',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@report',
        'namespace' => NULL,
        'prefix' => 'api/accounting',
        'where' => 
        array (
        ),
        'as' => 'generated::GCUNqKd23y6nWwM5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kfGn8lW0gTCiXUcx' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@appointmentStats',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@appointmentStats',
        'namespace' => NULL,
        'prefix' => 'api/accounting',
        'where' => 
        array (
        ),
        'as' => 'generated::kfGn8lW0gTCiXUcx',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::dv1LVpou03vh5JV7' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@export',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@export',
        'namespace' => NULL,
        'prefix' => 'api/accounting',
        'where' => 
        array (
        ),
        'as' => 'generated::dv1LVpou03vh5JV7',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::50exziXVKwDu0uSJ' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@transactions',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@transactions',
        'namespace' => NULL,
        'prefix' => 'api/accounting/transactions',
        'where' => 
        array (
        ),
        'as' => 'generated::50exziXVKwDu0uSJ',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::qavyRe6c8mCoPt4Z' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@storeTransaction',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@storeTransaction',
        'namespace' => NULL,
        'prefix' => 'api/accounting/transactions',
        'where' => 
        array (
        ),
        'as' => 'generated::qavyRe6c8mCoPt4Z',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::kjxJqmJVjtQFK2mE' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\AccountingController@markAsPaid',
        'controller' => 'App\\Http\\Controllers\\Api\\AccountingController@markAsPaid',
        'namespace' => NULL,
        'prefix' => 'api/accounting/transactions',
        'where' => 
        array (
        ),
        'as' => 'generated::kjxJqmJVjtQFK2mE',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::LfLPW5i5jC9sg8pf' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\UnreadMessagesController@index',
        'controller' => 'App\\Http\\Controllers\\Api\\UnreadMessagesController@index',
        'namespace' => NULL,
        'prefix' => 'api/messages',
        'where' => 
        array (
        ),
        'as' => 'generated::LfLPW5i5jC9sg8pf',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::3KngNs19XeqTEx6N' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@consentForms',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@consentForms',
        'namespace' => NULL,
        'prefix' => 'api/traceability/consent-forms',
        'where' => 
        array (
        ),
        'as' => 'generated::3KngNs19XeqTEx6N',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::MxUcsyWsbQUsCG3i' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@storeConsentForm',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@storeConsentForm',
        'namespace' => NULL,
        'prefix' => 'api/traceability/consent-forms',
        'where' => 
        array (
        ),
        'as' => 'generated::MxUcsyWsbQUsCG3i',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::eqol8Oyv8C85uxv5' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@verifyConsentForm',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@verifyConsentForm',
        'namespace' => NULL,
        'prefix' => 'api/traceability/consent-forms',
        'where' => 
        array (
        ),
        'as' => 'generated::eqol8Oyv8C85uxv5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::BqpI4apTebUQQpea' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@storeParentalConsent',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@storeParentalConsent',
        'namespace' => NULL,
        'prefix' => 'api/traceability/consent-forms',
        'where' => 
        array (
        ),
        'as' => 'generated::BqpI4apTebUQQpea',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::WCeEoM7AcFBiz8Z5' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@traceabilityRecords',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@traceabilityRecords',
        'namespace' => NULL,
        'prefix' => 'api/traceability/records',
        'where' => 
        array (
        ),
        'as' => 'generated::WCeEoM7AcFBiz8Z5',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::Jq1uaVfy0oVBpl1B' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@storeTraceabilityRecord',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@storeTraceabilityRecord',
        'namespace' => NULL,
        'prefix' => 'api/traceability/records',
        'where' => 
        array (
        ),
        'as' => 'generated::Jq1uaVfy0oVBpl1B',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::6ym6NZmxTNQYExlg' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@addMaterials',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@addMaterials',
        'namespace' => NULL,
        'prefix' => 'api/traceability/records',
        'where' => 
        array (
        ),
        'as' => 'generated::6ym6NZmxTNQYExlg',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2wpNbTzHKVQUsfRN' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@addPhotos',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@addPhotos',
        'namespace' => NULL,
        'prefix' => 'api/traceability/records',
        'where' => 
        array (
        ),
        'as' => 'generated::2wpNbTzHKVQUsfRN',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::2ydAeS3jp0kltHVY' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@verifyTraceability',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@verifyTraceability',
        'namespace' => NULL,
        'prefix' => 'api/traceability/records',
        'where' => 
        array (
        ),
        'as' => 'generated::2ydAeS3jp0kltHVY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::VjfJEzfyNU14P14n' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@generateReport',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@generateReport',
        'namespace' => NULL,
        'prefix' => 'api/traceability/records',
        'where' => 
        array (
        ),
        'as' => 'generated::VjfJEzfyNU14P14n',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::JCe2uLMzRwEyb1Ib' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\Api\\TraceabilityController@statistics',
        'controller' => 'App\\Http\\Controllers\\Api\\TraceabilityController@statistics',
        'namespace' => NULL,
        'prefix' => 'api/traceability',
        'where' => 
        array (
        ),
        'as' => 'generated::JCe2uLMzRwEyb1Ib',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
      array (
      ),
      'bindingFields' => 
      array (
      ),
      'lockSeconds' => NULL,
      'waitSeconds' => NULL,
      'withTrashed' => false,
    ),
    'generated::meD3jVQ8yVNwbKhY' => 
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
                }";s:5:"scope";s:54:"Illuminate\\Foundation\\Configuration\\ApplicationBuilder";s:4:"this";N;s:4:"self";s:32:"0000000000000f150000000000000000";}}',
        'as' => 'generated::meD3jVQ8yVNwbKhY',
      ),
      'fallback' => false,
      'defaults' => 
      array (
      ),
      'wheres' => 
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
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:44:"function () {
    return \\view(\'welcome\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010f30000000000000000";}}',
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
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010f10000000000000000";}}',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@profile',
        'controller' => 'App\\Http\\Controllers\\TattooerController@profile',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@dashboard',
        'controller' => 'App\\Http\\Controllers\\TattooerController@dashboard',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@requests',
        'controller' => 'App\\Http\\Controllers\\TattooerController@requests',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@requestShow',
        'controller' => 'App\\Http\\Controllers\\TattooerController@requestShow',
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
    'tattooer.tattooer.pricing' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@pricing',
        'controller' => 'App\\Http\\Controllers\\TattooerController@pricing',
        'as' => 'tattooer.tattooer.pricing',
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
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:227:"function (\\App\\Models\\BookingRequest $bookingRequest) {
        return \\redirect()->route(\'tattooer.request.show\', $bookingRequest)
            ->with(\'info\', \'Veuillez utiliser la modale d\\\'acceptation sur cette page.\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010e70000000000000000";}}',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@acceptRequest',
        'controller' => 'App\\Http\\Controllers\\TattooerController@acceptRequest',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@requestReject',
        'controller' => 'App\\Http\\Controllers\\TattooerController@requestReject',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@reproposeDates',
        'controller' => 'App\\Http\\Controllers\\TattooerController@reproposeDates',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@calendar',
        'controller' => 'App\\Http\\Controllers\\TattooerController@calendar',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@calendarEvents',
        'controller' => 'App\\Http\\Controllers\\TattooerController@calendarEvents',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@calendarStore',
        'controller' => 'App\\Http\\Controllers\\TattooerController@calendarStore',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@calendarUpdate',
        'controller' => 'App\\Http\\Controllers\\TattooerController@calendarUpdate',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@calendarDestroy',
        'controller' => 'App\\Http\\Controllers\\TattooerController@calendarDestroy',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@messages',
        'controller' => 'App\\Http\\Controllers\\TattooerController@messages',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@messageShow',
        'controller' => 'App\\Http\\Controllers\\TattooerController@messageShow',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@messageSend',
        'controller' => 'App\\Http\\Controllers\\TattooerController@messageSend',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@completeBooking',
        'controller' => 'App\\Http\\Controllers\\TattooerController@completeBooking',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@markNoShow',
        'controller' => 'App\\Http\\Controllers\\TattooerController@markNoShow',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@clients',
        'controller' => 'App\\Http\\Controllers\\TattooerController@clients',
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
          2 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@createClient',
        'controller' => 'App\\Http\\Controllers\\TattooerController@createClient',
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
          2 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@storeClient',
        'controller' => 'App\\Http\\Controllers\\TattooerController@storeClient',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@clientShow',
        'controller' => 'App\\Http\\Controllers\\TattooerController@clientShow',
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
          2 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@updateClient',
        'controller' => 'App\\Http\\Controllers\\TattooerController@updateClient',
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
          2 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@uploadConsent',
        'controller' => 'App\\Http\\Controllers\\TattooerController@uploadConsent',
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
          2 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@storeDigitalConsent',
        'controller' => 'App\\Http\\Controllers\\TattooerController@storeDigitalConsent',
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
          2 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@deleteConsent',
        'controller' => 'App\\Http\\Controllers\\TattooerController@deleteConsent',
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
          2 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@storeClientTraceability',
        'controller' => 'App\\Http\\Controllers\\TattooerController@storeClientTraceability',
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
          2 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@uploadClientPhotos',
        'controller' => 'App\\Http\\Controllers\\TattooerController@uploadClientPhotos',
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
          2 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@deleteClientPhoto',
        'controller' => 'App\\Http\\Controllers\\TattooerController@deleteClientPhoto',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@updateClientNotes',
        'controller' => 'App\\Http\\Controllers\\TattooerController@updateClientNotes',
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
    'tattooer.tattooer.consent.store' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@storeConsent',
        'controller' => 'App\\Http\\Controllers\\TattooerController@storeConsent',
        'as' => 'tattooer.tattooer.consent.store',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@storeTraceability',
        'controller' => 'App\\Http\\Controllers\\TattooerController@storeTraceability',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@completeAppointment',
        'controller' => 'App\\Http\\Controllers\\TattooerController@completeAppointment',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@reportNoShow',
        'controller' => 'App\\Http\\Controllers\\TattooerController@reportNoShow',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@uploadClientTattooPhotos',
        'controller' => 'App\\Http\\Controllers\\TattooerController@uploadClientTattooPhotos',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@deleteClientMedia',
        'controller' => 'App\\Http\\Controllers\\TattooerController@deleteClientMedia',
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
    'tattooer.tattooer.client-requests' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@clientRequests',
        'controller' => 'App\\Http\\Controllers\\TattooerController@clientRequests',
        'as' => 'tattooer.tattooer.client-requests',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@portfolio',
        'controller' => 'App\\Http\\Controllers\\TattooerController@portfolio',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@portfolioUpload',
        'controller' => 'App\\Http\\Controllers\\TattooerController@portfolioUpload',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@portfolioBeforeAfterStore',
        'controller' => 'App\\Http\\Controllers\\TattooerController@portfolioBeforeAfterStore',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@portfolioDestroy',
        'controller' => 'App\\Http\\Controllers\\TattooerController@portfolioDestroy',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@portfolioBeforeAfterDestroy',
        'controller' => 'App\\Http\\Controllers\\TattooerController@portfolioBeforeAfterDestroy',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@settings',
        'controller' => 'App\\Http\\Controllers\\TattooerController@settings',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@settingsUpdate',
        'controller' => 'App\\Http\\Controllers\\TattooerController@settingsUpdate',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@settingsAftercareUpdate',
        'controller' => 'App\\Http\\Controllers\\TattooerController@settingsAftercareUpdate',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@settingsPricingUpdate',
        'controller' => 'App\\Http\\Controllers\\TattooerController@settingsPricingUpdate',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@deleteAvatar',
        'controller' => 'App\\Http\\Controllers\\TattooerController@deleteAvatar',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@deleteBanner',
        'controller' => 'App\\Http\\Controllers\\TattooerController@deleteBanner',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@settingsUpdateSchedule',
        'controller' => 'App\\Http\\Controllers\\TattooerController@settingsUpdateSchedule',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@settingsUpdatePassword',
        'controller' => 'App\\Http\\Controllers\\TattooerController@settingsUpdatePassword',
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
    'tattooer.tattooer.settings.hours.update' => 
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@updateHours',
        'controller' => 'App\\Http\\Controllers\\TattooerController@updateHours',
        'as' => 'tattooer.tattooer.settings.hours.update',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@payments',
        'controller' => 'App\\Http\\Controllers\\TattooerController@payments',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@compliance',
        'controller' => 'App\\Http\\Controllers\\TattooerController@compliance',
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
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:80:"function () {
    return \\view(\'livewire.tattooer.pending-verification-full\');
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010ee0000000000000000";}}',
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
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000109f0000000000000000";}}',
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
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000109d0000000000000000";}}',
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
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000109b0000000000000000";}}',
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
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010990000000000000000";}}',
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
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:138:"function (\\Illuminate\\Http\\Request $request) {
    return \\app(\\App\\Http\\Controllers\\RegisterController::class)->submitClient($request);
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010950000000000000000";}}',
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
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:140:"function (\\Illuminate\\Http\\Request $request) {
    return \\app(\\App\\Http\\Controllers\\RegisterController::class)->submitTattooer($request);
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010930000000000000000";}}',
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
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:139:"function (\\Illuminate\\Http\\Request $request) {
    return \\app(\\App\\Http\\Controllers\\RegisterController::class)->submitPiercer($request);
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010910000000000000000";}}',
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
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:138:"function (\\Illuminate\\Http\\Request $request) {
    return \\app(\\App\\Http\\Controllers\\RegisterController::class)->submitStudio($request);
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000108f0000000000000000";}}',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@index',
        'controller' => 'App\\Http\\Controllers\\ClientController@index',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@dashboard',
        'controller' => 'App\\Http\\Controllers\\ClientController@dashboard',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@bookingRequests',
        'controller' => 'App\\Http\\Controllers\\ClientController@bookingRequests',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@bookingRequestShow',
        'controller' => 'App\\Http\\Controllers\\ClientController@bookingRequestShow',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@selectProposedDate',
        'controller' => 'App\\Http\\Controllers\\ClientController@selectProposedDate',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@requestAlternativeDates',
        'controller' => 'App\\Http\\Controllers\\ClientController@requestAlternativeDates',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@chat',
        'controller' => 'App\\Http\\Controllers\\ClientController@chat',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@sendMessage',
        'controller' => 'App\\Http\\Controllers\\ClientController@sendMessage',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@reviews',
        'controller' => 'App\\Http\\Controllers\\ClientController@reviews',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@complaints',
        'controller' => 'App\\Http\\Controllers\\ClientController@complaints',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@createReview',
        'controller' => 'App\\Http\\Controllers\\ClientController@createReview',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@createComplaint',
        'controller' => 'App\\Http\\Controllers\\ClientController@createComplaint',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@bookingRequestCancel',
        'controller' => 'App\\Http\\Controllers\\ClientController@bookingRequestCancel',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@bookingRequestDelete',
        'controller' => 'App\\Http\\Controllers\\ClientController@bookingRequestDelete',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@profile',
        'controller' => 'App\\Http\\Controllers\\TattooerController@profile',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@dashboard',
        'controller' => 'App\\Http\\Controllers\\TattooerController@dashboard',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@requests',
        'controller' => 'App\\Http\\Controllers\\TattooerController@requests',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@requestShow',
        'controller' => 'App\\Http\\Controllers\\TattooerController@requestShow',
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
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:227:"function (\\App\\Models\\BookingRequest $bookingRequest) {
        return \\redirect()->route(\'pierceur.request.show\', $bookingRequest)
            ->with(\'info\', \'Veuillez utiliser la modale d\\\'acceptation sur cette page.\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010780000000000000000";}}',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@acceptRequest',
        'controller' => 'App\\Http\\Controllers\\TattooerController@acceptRequest',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@requestReject',
        'controller' => 'App\\Http\\Controllers\\TattooerController@requestReject',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@reproposeDates',
        'controller' => 'App\\Http\\Controllers\\TattooerController@reproposeDates',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@calendar',
        'controller' => 'App\\Http\\Controllers\\TattooerController@calendar',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@calendarEvents',
        'controller' => 'App\\Http\\Controllers\\TattooerController@calendarEvents',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@calendarStore',
        'controller' => 'App\\Http\\Controllers\\TattooerController@calendarStore',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@calendarUpdate',
        'controller' => 'App\\Http\\Controllers\\TattooerController@calendarUpdate',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@calendarDestroy',
        'controller' => 'App\\Http\\Controllers\\TattooerController@calendarDestroy',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@messages',
        'controller' => 'App\\Http\\Controllers\\TattooerController@messages',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@messageShow',
        'controller' => 'App\\Http\\Controllers\\TattooerController@messageShow',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@messageSend',
        'controller' => 'App\\Http\\Controllers\\TattooerController@messageSend',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@completeBooking',
        'controller' => 'App\\Http\\Controllers\\TattooerController@completeBooking',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@markNoShow',
        'controller' => 'App\\Http\\Controllers\\TattooerController@markNoShow',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@clients',
        'controller' => 'App\\Http\\Controllers\\TattooerController@clients',
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
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@createClient',
        'controller' => 'App\\Http\\Controllers\\TattooerController@createClient',
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
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@storeClient',
        'controller' => 'App\\Http\\Controllers\\TattooerController@storeClient',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@clientShow',
        'controller' => 'App\\Http\\Controllers\\TattooerController@clientShow',
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
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@updateClient',
        'controller' => 'App\\Http\\Controllers\\TattooerController@updateClient',
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
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@uploadConsent',
        'controller' => 'App\\Http\\Controllers\\TattooerController@uploadConsent',
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
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@storeDigitalConsent',
        'controller' => 'App\\Http\\Controllers\\TattooerController@storeDigitalConsent',
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
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@deleteConsent',
        'controller' => 'App\\Http\\Controllers\\TattooerController@deleteConsent',
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
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@storeClientTraceability',
        'controller' => 'App\\Http\\Controllers\\TattooerController@storeClientTraceability',
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
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@uploadClientPhotos',
        'controller' => 'App\\Http\\Controllers\\TattooerController@uploadClientPhotos',
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
          3 => 'pro',
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@deleteClientPhoto',
        'controller' => 'App\\Http\\Controllers\\TattooerController@deleteClientPhoto',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@updateClientNotes',
        'controller' => 'App\\Http\\Controllers\\TattooerController@updateClientNotes',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@clientRequests',
        'controller' => 'App\\Http\\Controllers\\TattooerController@clientRequests',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@storeConsent',
        'controller' => 'App\\Http\\Controllers\\TattooerController@storeConsent',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@storeTraceability',
        'controller' => 'App\\Http\\Controllers\\TattooerController@storeTraceability',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@completeAppointment',
        'controller' => 'App\\Http\\Controllers\\TattooerController@completeAppointment',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@reportNoShow',
        'controller' => 'App\\Http\\Controllers\\TattooerController@reportNoShow',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@uploadClientTattooPhotos',
        'controller' => 'App\\Http\\Controllers\\TattooerController@uploadClientTattooPhotos',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@deleteClientMedia',
        'controller' => 'App\\Http\\Controllers\\TattooerController@deleteClientMedia',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@portfolio',
        'controller' => 'App\\Http\\Controllers\\TattooerController@portfolio',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@portfolioUpload',
        'controller' => 'App\\Http\\Controllers\\TattooerController@portfolioUpload',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@portfolioDestroy',
        'controller' => 'App\\Http\\Controllers\\TattooerController@portfolioDestroy',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@settings',
        'controller' => 'App\\Http\\Controllers\\TattooerController@settings',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@settingsUpdate',
        'controller' => 'App\\Http\\Controllers\\TattooerController@settingsUpdate',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@settingsAftercareUpdate',
        'controller' => 'App\\Http\\Controllers\\TattooerController@settingsAftercareUpdate',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@settingsPricingUpdate',
        'controller' => 'App\\Http\\Controllers\\TattooerController@settingsPricingUpdate',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@deleteAvatar',
        'controller' => 'App\\Http\\Controllers\\TattooerController@deleteAvatar',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@deleteBanner',
        'controller' => 'App\\Http\\Controllers\\TattooerController@deleteBanner',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@settingsUpdateSchedule',
        'controller' => 'App\\Http\\Controllers\\TattooerController@settingsUpdateSchedule',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@settingsUpdatePassword',
        'controller' => 'App\\Http\\Controllers\\TattooerController@settingsUpdatePassword',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@updateHours',
        'controller' => 'App\\Http\\Controllers\\TattooerController@updateHours',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@payments',
        'controller' => 'App\\Http\\Controllers\\TattooerController@payments',
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
        ),
        'uses' => 'App\\Http\\Controllers\\TattooerController@compliance',
        'controller' => 'App\\Http\\Controllers\\TattooerController@compliance',
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
        'uses' => 'App\\Http\\Controllers\\StudioController@publicProfile',
        'controller' => 'App\\Http\\Controllers\\StudioController@publicProfile',
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
        'uses' => 'App\\Http\\Controllers\\StudioController@publicProfile',
        'controller' => 'App\\Http\\Controllers\\StudioController@publicProfile',
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
        'uses' => 'App\\Http\\Controllers\\StudioController@acceptInvitation',
        'controller' => 'App\\Http\\Controllers\\StudioController@acceptInvitation',
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
        'uses' => 'App\\Http\\Controllers\\StudioController@processInvitation',
        'controller' => 'App\\Http\\Controllers\\StudioController@processInvitation',
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
        ),
        'uses' => 'App\\Http\\Controllers\\StudioController@artists',
        'controller' => 'App\\Http\\Controllers\\StudioController@artists',
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
        ),
        'uses' => 'App\\Http\\Controllers\\StudioController@createArtist',
        'controller' => 'App\\Http\\Controllers\\StudioController@createArtist',
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
        ),
        'uses' => 'App\\Http\\Controllers\\StudioController@storeArtist',
        'controller' => 'App\\Http\\Controllers\\StudioController@storeArtist',
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
        ),
        'uses' => 'App\\Http\\Controllers\\StudioController@inviteArtist',
        'controller' => 'App\\Http\\Controllers\\StudioController@inviteArtist',
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
        ),
        'uses' => 'App\\Http\\Controllers\\StudioController@removeArtist',
        'controller' => 'App\\Http\\Controllers\\StudioController@removeArtist',
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
        ),
        'uses' => 'App\\Http\\Controllers\\StudioController@toggleArtist',
        'controller' => 'App\\Http\\Controllers\\StudioController@toggleArtist',
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
        ),
        'uses' => 'App\\Http\\Controllers\\StudioController@planning',
        'controller' => 'App\\Http\\Controllers\\StudioController@planning',
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
        ),
        'uses' => 'App\\Http\\Controllers\\StudioController@billing',
        'controller' => 'App\\Http\\Controllers\\StudioController@billing',
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
        ),
        'uses' => 'App\\Http\\Controllers\\StudioController@stats',
        'controller' => 'App\\Http\\Controllers\\StudioController@stats',
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
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:65:"function () {
        return \\view(\'professionnels.index\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010290000000000000000";}}',
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
    'studio.studio.compliance' => 
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
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:62:"function () {
        return \\view(\'studio.compliance\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010270000000000000000";}}',
        'as' => 'studio.studio.compliance',
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
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:65:"function () {
        return \\view(\'professionnels.index\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000101e0000000000000000";}}',
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
        ),
        'uses' => 'O:55:"Laravel\\SerializableClosure\\UnsignedSerializableClosure":1:{s:12:"serializable";O:46:"Laravel\\SerializableClosure\\Serializers\\Native":5:{s:3:"use";a:0:{}s:8:"function";s:69:"function () {
        return \\view(\'studio-artist.compliance\');
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"000000000000101c0000000000000000";}}',
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
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010390000000000000000";}}',
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
}";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010190000000000000000";}}',
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
        'uses' => 'App\\Http\\Controllers\\Tattooer\\AccountController@delete',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\AccountController@delete',
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
        'uses' => 'App\\Http\\Controllers\\Tattooer\\AccountController@delete',
        'controller' => 'App\\Http\\Controllers\\Tattooer\\AccountController@delete',
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
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010080000000000000000";}}',
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
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010060000000000000000";}}',
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
        'uses' => 'App\\Http\\Controllers\\ClientController@chat',
        'controller' => 'App\\Http\\Controllers\\ClientController@chat',
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
    }";s:5:"scope";s:37:"Illuminate\\Routing\\RouteFileRegistrar";s:4:"this";N;s:4:"self";s:32:"00000000000010030000000000000000";}}',
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
    'generated::JlVkn3XcqIL3nFKB' => 
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
        'as' => 'generated::JlVkn3XcqIL3nFKB',
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
          3 => 'password.confirm',
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
                }";s:5:"scope";s:47:"Illuminate\\Filesystem\\FilesystemServiceProvider";s:4:"this";N;s:4:"self";s:32:"000000000000100d0000000000000000";}}',
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
