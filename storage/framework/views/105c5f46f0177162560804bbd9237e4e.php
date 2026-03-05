<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title><?php echo $__env->yieldContent('title'); ?> — Ink&Pik</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 11px;
            line-height: 1.5;
            color: #1a1a1a;
            padding: 30px 40px;
        }

        /* Header */
        .pdf-header {
            display: table;
            width: 100%;
            margin-bottom: 25px;
            border-bottom: 2px solid #C97435;
            padding-bottom: 15px;
        }

        .pdf-header-left {
            display: table-cell;
            vertical-align: middle;
            width: 60%;
        }

        .pdf-header-right {
            display: table-cell;
            vertical-align: middle;
            width: 40%;
            text-align: right;
        }

        .pdf-logo {
            font-size: 22px;
            font-weight: bold;
            color: #C97435;
            letter-spacing: 1px;
        }

        .pdf-logo span {
            color: #1a1a1a;
        }

        .pdf-subtitle {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }

        .pdf-doc-type {
            font-size: 14px;
            font-weight: bold;
            color: #1a1a1a;
        }

        .pdf-doc-date {
            font-size: 9px;
            color: #666;
            margin-top: 3px;
        }

        .pdf-doc-ref {
            font-size: 9px;
            color: #999;
        }

        /* Sections */
        h2 {
            font-size: 13px;
            color: #C97435;
            border-bottom: 1px solid #e0d5c8;
            padding-bottom: 5px;
            margin: 20px 0 10px 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        h3 {
            font-size: 11px;
            color: #333;
            margin: 12px 0 6px 0;
        }

        /* Info blocks */
        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-col {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 15px;
        }

        .info-label {
            font-size: 9px;
            color: #888;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .info-value {
            font-size: 11px;
            color: #1a1a1a;
            margin-bottom: 6px;
        }

        /* Tables */
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }

        th {
            background-color: #f5f0eb;
            color: #333;
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: 0.3px;
            padding: 8px 10px;
            text-align: left;
            border-bottom: 1px solid #d4c9bc;
        }

        td {
            padding: 7px 10px;
            border-bottom: 1px solid #eee;
            font-size: 10px;
        }

        tr:nth-child(even) td {
            background-color: #fafaf8;
        }

        /* Signature block */
        .signature-block {
            margin-top: 30px;
            display: table;
            width: 100%;
        }

        .signature-col {
            display: table-cell;
            width: 45%;
            vertical-align: top;
        }

        .signature-spacer {
            display: table-cell;
            width: 10%;
        }

        .signature-line {
            border-bottom: 1px solid #999;
            height: 60px;
            margin-top: 10px;
        }

        .signature-label {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }

        /* Footer */
        .pdf-footer {
            position: fixed;
            bottom: 20px;
            left: 40px;
            right: 40px;
            border-top: 1px solid #e0d5c8;
            padding-top: 8px;
            font-size: 8px;
            color: #999;
            text-align: center;
        }

        /* Alert box */
        .alert-box {
            background-color: #fff8f0;
            border: 1px solid #C97435;
            border-radius: 4px;
            padding: 10px 12px;
            margin: 10px 0;
            font-size: 10px;
        }

        .alert-box strong {
            color: #C97435;
        }

        /* Checklist */
        .checklist {
            list-style: none;
        }

        .checklist li {
            padding: 4px 0;
            padding-left: 18px;
            position: relative;
            font-size: 10px;
        }

        .checklist li:not(.checked)::before {
            content: "\2610";
            position: absolute;
            left: 0;
            color: #C97435;
        }

        /* Utils */
        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .text-small {
            font-size: 9px;
        }

        .text-muted {
            color: #888;
        }

        .mt-10 {
            margin-top: 10px;
        }

        .mt-20 {
            margin-top: 20px;
        }

        .mb-10 {
            margin-bottom: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    
    <div class="pdf-header">
        <div class="pdf-header-left">
            <div class="pdf-logo">Ink<span>&amp;</span>Pik</div>
            <div class="pdf-subtitle">Plateforme de mise en relation — Art corporel professionnel</div>
        </div>
        <div class="pdf-header-right">
            <div class="pdf-doc-type"><?php echo $__env->yieldContent('doc-type'); ?></div>
            <div class="pdf-doc-date"><?php echo $__env->yieldContent('doc-date', now()->format('d/m/Y à H:i')); ?></div>
            <div class="pdf-doc-ref"><?php echo $__env->yieldContent('doc-ref', ''); ?></div>
        </div>
    </div>

    
    <?php echo $__env->yieldContent('content'); ?>

    
    <div class="pdf-footer">
        Ink&amp;Pik — [Raison sociale] — SIRET : [SIRET] — <?php echo e(config('app.url')); ?>

        <br>
        Document généré le <?php echo e(now()->format('d/m/Y à H:i')); ?> — Ce document ne constitue pas une facture au sens fiscal
        du terme.
    </div>
</body>

</html>
<?php /**PATH C:\laragon\www\tattoolib-saas\resources\views/pdf/layout.blade.php ENDPATH**/ ?>