<?php
/**
 * Controller for displaying a robots.txt file for each subsite.
 *
 * <code>
 * http://site.com/robots.txt
 * </code>
 *
 * Original inspiration from `silverstripe-multisites`
 * @see https://github.com/silverstripe-australia/silverstripe-multisites/
 */
class RobotsTxtController extends Controller
{
    /** @var array Allowed controller actions */
    private static $allowed_actions = [
        'robots.txt' => 'index',
        ''           => 'index',
    ];

    /** @var string Template name */
    protected $template = 'RobotsTxt';

    /**
     * Get customised robots.txt
     * @return text robots.txt file
     */
    public function index()
    {
        // get current subsite
        $subsite = Subsite::currentSubsite();
        if (!$subsite) {
            return $this->httpError(404);
        }

        // set response content type
        $this->getResponse()->addHeader('Content-Type', 'text/plain; charset="utf-8"');

        $data = ArrayData::create([
            'Subsite' => $subsite,
            'isLive'  => true //Director::isLive(),
        ]);

        return $this->customise($data)->renderWith($this->getTemplate());
    }

    /**
     * Get list of disallowed folder for this subsite
     * @return ArrayList List of disallowed folders
     */
    public function getDisallowedFolderList()
    {
        if (!$subsiteID = Subsite::currentSubsiteID()) {
            return null;
        }

        $folders = $this->getFoldersFromOtherSubsites($subsiteID);

        return $this->getReducedFolderList($folders);
    }

    /**
     * Get folders from other subsites
     * @param  int   $excludeID ID of current subsite (to exclude)
     * @return array            List of folders from other subsites
     */
    public function getFoldersFromOtherSubsites($excludeID)
    {
        $result = SQLSelect::create(['Filename'])
            ->setFrom('File')
            ->addWhere([
                "\"ClassName\" = 'Folder'",
                "\"SubsiteID\" NOT IN (0, {$excludeID})",
            ])
            ->addOrderBy('Filename')
            ->execute();

        $folders = ArrayList::create();
        foreach ($result as $row) {
            $folders->push(ArrayData::create($row));
        }

        return $folders;
    }

    /**
     * Get list of folders reduced to common paths
     * @param  ArrayList $folders Sorted folder list
     * @return ArrayList          Reduced list of folders
     */
    public function getReducedFolderList(ArrayList $folders)
    {
        $common = ArrayList::create();

        // examine folders and remove items with matching partial
        foreach ($folders as $folder) {
            // check if file name begins with saved comparison
            if (isset($folder->Filename)
                && (!isset($compare) || strpos($folder->Filename, $compare) !== 0)
            ) {
                // save folder if unique
                $common->push($folder);
                // update the comparison
                $compare = $folder->Filename;
            }
        }

        return $common;
    }

    /**
     * Get template name
     * @return string Name of template
     */
    public function getTemplate()
    {
        return $this->template;
    }
}
