<?php
namespace Ababilithub\FlexPhp\Package\Audit\V1\Contract;

interface Audit
{
    public function createdAt(): string;
    public function createdBy(): string;
    public function updatedAt(): string;
    public function updatedBy(): string;
    public function createdFromIP(): string;
    public function updatedFromIP(): string;
    public function createdFromUserAgent(): string;
    public function updatedFromUserAgent(): string;
    
}