<?php

declare(strict_types=1);

namespace Napp\Xray\Submission;

use Pkerrigan\Xray\Segment;
use Pkerrigan\Xray\Submission\DaemonSegmentSubmitter as SubmissionDaemonSegmentSubmitter;
use Pkerrigan\Xray\Submission\SegmentSubmitter;

class DaemonSegmentSubmitter implements SegmentSubmitter
{
    /**
     * @var SubmissionDaemonSegmentSubmitter
     */
    private $submitter;

    /**
     * @var string
     */
    private $host;

    /**
     * @var int
     */
    private $port;

    public function __construct()
    {
        $this->host = env('_AWS_XRAY_DAEMON_ADDRESS');
        $this->port = (int) env('_AWS_XRAY_DAEMON_PORT');
    }

    /**
     * Get or create the Daemon submitter.
     */
    protected function submitter(): SubmissionDaemonSegmentSubmitter
    {
        if (is_null($this->submitter)) {
            $this->submitter = new SubmissionDaemonSegmentSubmitter(
                $this->host,
                $this->port
            );
        }

        return $this->submitter;
    }

    public function submitSegment(Segment $segment): void
    {
        $this->submitter()->submitSegment($segment);
    }
}
