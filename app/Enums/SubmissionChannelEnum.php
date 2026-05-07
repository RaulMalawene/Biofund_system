<?php

namespace App\Enums;

enum SubmissionChannelEnum: string
{
    case GreenLine        = 'green_line';
    case Email            = 'email';
    case Phone            = 'phone';
    case CommunityMeeting = 'community_meeting';

    public function label(): string
    {
        return match ($this) {
            self::GreenLine        => 'Linha Verde',
            self::Email            => 'Email',
            self::Phone            => 'Telefone',
            self::CommunityMeeting => 'Reunião Comunitária',
        };
    }
}
