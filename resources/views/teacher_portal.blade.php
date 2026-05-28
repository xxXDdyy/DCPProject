@extends('layout.format')

@section('title')
    Teacher Portal
@endsection

@section('Header')
    @parent
@endsection

@section('Content')
    <style>
        .portal-shell{
            background: linear-gradient(155deg, rgba(15, 118, 110, 0.08), rgba(14, 165, 233, 0.08));
            border: 1px solid rgba(20, 184, 166, 0.22);
            border-radius: 20px;
            padding: 34px 28px;
            box-shadow: inset 0 1px 0 rgba(255, 255, 255, 0.6);
        }

        .portal-badge{
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border-radius: 999px;
            padding: 8px 14px;
            font-size: 0.8rem;
            font-weight: 700;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            color: #115e59;
            background: rgba(15, 118, 110, 0.10);
            border: 1px solid rgba(15, 118, 110, 0.20);
        }

        .portal-dot{
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #14b8a6;
        }

        .portal-title{
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.25;
            margin: 16px 0 8px;
            color: #0f172a;
        }

        .portal-name{
            color: #0f766e;
        }

        .portal-text{
            margin: 0;
            color: #475569;
            font-size: 1.04rem;
            line-height: 1.8;
            max-width: 62ch;
        }

        @media (max-width: 768px){
            .portal-shell{
                padding: 24px 20px;
                border-radius: 16px;
            }

            .portal-title{
                font-size: 1.6rem;
            }
        }
    </style>

    <div class="portal-shell">
        <span class="portal-badge"><span class="portal-dot"></span>Teacher Portal</span>
        <h1 class="portal-title">
            Welcome to your teacher portal account, <span class="portal-name">{{ $username }}</span>.
        </h1>
        <p class="portal-text">
            This page is teacher page.
        </p>
    </div>
@endsection

@section('Footer')
    @parent
@endsection
