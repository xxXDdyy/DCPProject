@extends('layout.format')

@section('title')
    Student Portal
@endsection

@section('Header')
    @parent
@endsection

@section('Content')
    <style>
        .portal-shell{
            background: linear-gradient(155deg, rgba(29, 78, 216, 0.08), rgba(14, 165, 233, 0.08));
            border: 1px solid rgba(59, 130, 246, 0.20);
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
            color: #1e3a8a;
            background: rgba(30, 64, 175, 0.10);
            border: 1px solid rgba(30, 64, 175, 0.20);
        }

        .portal-dot{
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #3b82f6;
        }

        .portal-title{
            font-size: 2rem;
            font-weight: 800;
            line-height: 1.25;
            margin: 16px 0 8px;
            color: #0f172a;
        }

        .portal-name{
            color: #1d4ed8;
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
        @if(session('message'))
            <div class="alert alert-success rounded-3">
                {{ session('message') }}
            </div>
        @endif

        <span class="portal-badge"><span class="portal-dot"></span>Student Portal</span>
        <h1 class="portal-title">
            Welcome to your student portal account, <span class="portal-name">{{ $username }}</span>.
        </h1>
        <p class="portal-text">
            This page is student page.
        </p>
    </div>
@endsection

@section('Footer')
    @parent
@endsection
