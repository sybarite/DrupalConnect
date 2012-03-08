<?php
/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information, see
 * <https://github.com/epicwhale/drupalconnect>.
 */
namespace DrupalConnect;

/**
 * The DocumentManager class is the central access point for retreiving (and in future managing persistence) of documents
 *
 * @link https://github.com/epicwhale/drupalconnect
 * @author Dayson Pais <dayson@epicwhale.org>
 */
class DocumentManager
{
    /**
     * @var Connection
     */
    protected $_connection;

    /**
     * Mapping of Document --> Repository
     *
     * @var array
     */
    protected $_documentRepositoryMapping = array(
        'DrupalConnect\Document\Node' => 'DrupalConnect\Repository\Node',
        'DrupalConnect\Document\File' => 'DrupalConnect\Repository\File',
        'DrupalConnect\Document\File\Image' => 'DrupalConnect\Repository\File\Image'
    );

    /**
     * Mapping of Document --> Hydrator
     *
     * @var array
     */
    protected $_documentHydratorMapping = array(
        'DrupalConnect\Document\Node' => 'DrupalConnect\Hydrator\Node',
        'DrupalConnect\Document\File' => 'DrupalConnect\Hydrator\File',
        'DrupalConnect\Document\File\Image' => 'DrupalConnect\Hydrator\File\Image'
    );

    /**
     * @var array
     */
    protected $_config;

    /**
     * @param Connection $connection
     * @param array $config
     */
    public function __construct(\DrupalConnect\Connection $connection, array $config)
    {
        $this->_connection = $connection;
        $this->_config = $config;
    }

    /**
     * @return Connection
     */
    public function getConnection()
    {
        return $this->_connection;
    }

    /**
     * Create a Query Builder for a Document Type
     *
     * @param string $documentName
     * @return Query\Builder
     */
    public function createQueryBuilder($documentName)
    {
        return new \DrupalConnect\Query\Builder($this, $documentName);
    }

    /**
     * Get the Repository for a Document Type
     *
     * @param $documentName
     * @return Repository
     * @throws Exception
     */
    public function getRepository($documentName)
    {
        if (!isset($this->_documentRepositoryMapping[$documentName]))
        {
            throw new Exception("No registered Repository for document name: $documentName");
        }

        return new $this->_documentRepositoryMapping[$documentName]($this, $documentName);
    }

    /**
     * Get the Hydrator for a Document Type
     *
     * @param $documentName
     * @return Hydrator
     * @throws Exception
     */
    public function getHydrator($documentName)
    {
        if (!isset($this->_documentHydratorMapping[$documentName]))
        {
            throw new Exception("No registered Hydrator for document name: $documentName");
        }

        return new $this->_documentHydratorMapping[$documentName]($this, $documentName);
    }

    /**
     * @param null|string $key
     * @return array
     */
    public function getConfig($key = null)
    {
        if ($key)
        {
            return isset($this->_config[$key]) ? $this->_config[$key] : null;
        }
        return $this->_config;
    }


}
